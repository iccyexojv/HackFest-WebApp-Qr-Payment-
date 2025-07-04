<?php

namespace App\Filament\Wallet\Actions\WalletActions;

use App\Wallet\Enums\WalletTransactionMetaType;
use App\Wallet\Enums\WalletHolderType;
use App\Wallet\Enums\WalletType;
use Exception;
use Filament\Facades\Filament;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class WithdrawAction extends BaseWalletAction
{
    public static function actionName(): string
    {
        return 'withdraw';
    }

    public static function form(): array
    {
        return [
            Fieldset::make('Wallet Holder')
                ->columns(1)
                ->schema([
                    Select::make('holder_type')
                        ->options(WalletHolderType::class)
                        ->reactive()
                        ->required(),
                    Select::make('holder_id')
                        ->label('Holder')
                        ->reactive()
                        ->preload()
                        ->searchable()
                        ->options(function (Get $get) {
                            $holderType = WalletHolderType::tryFrom($get('holder_type'));
                            if (is_null($holderType)) {
                                return [];
                            }

                            return self::getHolderOptionsForHolderType($holderType);
                        })
                ]),
            Fieldset::make('Amount')
                ->columns(1)
                ->schema([
                    TextInput::make('amount')
                    ->minValue(1)
                        ->hiddenLabel()
                        ->reactive()
                        ->numeric(),
                ]),
        ];
    }

    public static function summary(): array
    {
        return [
            Section::make("Withdraw Amount")
                ->schema([
                    Placeholder::make('withdraw_amount')
                        ->hiddenLabel()
                        ->content(function (Get $get) {
                            $amount = $get('amount');
                            return $amount . " " . WalletType::FEST_POINT->getLabel();
                        }),
                ]),
            Section::make("Payer")
                ->schema([
                    Placeholder::make('reveiver_details')
                        ->hiddenLabel()
                        ->content(function (Get $get) {
                            $payerId = $get('holder_id') ?? '-';
                            $payerType = WalletHolderType::tryFrom($get('holder_type'));

                            if (is_null($payerId) || is_null($payerType)) {
                                return "Unknown";
                            }

                            $payer = $payerType?->getHolderClass()::find($payerId);

                            if (is_null($payer)) {
                                return "Unknown";
                            }

                            $summary = "ID: " . $payerId . " <br>";
                            $summary .= "Type: " . ($payerType?->getLabel() ?? "-") . " <br>";
                            $summary .= "Name: " . $payer->name;

                            return new HtmlString($summary);
                        }),
                ]),
            static::getProcessedByField(),
        ];
    }

    public static function action(array $data): void
    {
        $holderType = WalletHolderType::tryFrom($data['holder_type'])->getHolderClass();
        $holderId = $data['holder_id'];
        $holder = $holderType::find($holderId);
        $amount = $data['amount'];

        $wallet = WalletType::FEST_POINT->of($holder);

        if ($wallet->balance < $amount) {
            Notification::make()
                ->title('Withdraw failed')
                ->body('Wallet does not have sufficient balance.')
                ->danger()
                ->send();

            return;
        }

        $wallet->withdraw($amount, WalletTransactionMetaType::WITHDRAW->getUserMeta());

        Notification::make()
            ->title('Withdrawal successful')
            ->body('You have successfully withdrawn ' . $amount . ' ' . WalletType::FEST_POINT->getLabel())
            ->success()
            ->send();
    }
}
