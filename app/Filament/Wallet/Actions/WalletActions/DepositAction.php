<?php

namespace App\Filament\Wallet\Actions\WalletActions;

use App\Wallet\Enums\WalletTransactionMetaType;
use App\Wallet\Enums\WalletHolderType;
use App\Wallet\Enums\WalletType;
use Exception;
use Filament\Actions\Action;
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

class DepositAction extends BaseWalletAction
{

    public static function actionName(): string
    {
        return 'deposit';
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
                        }),
                        Select::make('wallet_type')
                            ->options(WalletType::class)
                            ->required()
                            ->native(false),
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
            Section::make("Deposit Amount")
                ->schema([
                    Placeholder::make('deposit_amount')
                        ->hiddenLabel()
                        ->content(function (Get $get) {
                            $amount = $get('amount');
                            $walletType = WalletType::tryFrom($get('wallet_type') ?? '');
                            return $amount . " " . $walletType?->getCurrency() ?? 'N/A';
                        }),
                ]),
            Section::make("Receiver")
                ->schema([
                    Placeholder::make('reveiver_details')
                        ->hiddenLabel()
                        ->content(function (Get $get) {
                            $receiverId = $get('holder_id') ?? '-';
                            $receiverType = WalletHolderType::tryFrom($get('holder_type'));

                            if (is_null($receiverId) || is_null($receiverType)) {
                                return "Unknown";
                            }

                            $receiver = $receiverType?->getHolderClass()::find($receiverId);

                            if (is_null($receiver)) {
                                return "Unknown";
                            }

                            $summary = "ID: " . $receiverId . " <br>";
                            $summary .= "Type: " . ($receiverType?->getLabel() ?? "-") . " <br>";
                            $summary .= "Name: " . $receiver->name;

                            return new HtmlString($summary);
                        }),
                ]),
            static::getProcessedByField()
        ];
    }

    public static function action(array $data): void
    {
        $holderType = WalletHolderType::tryFrom($data['holder_type'])->getHolderClass();
        $holderId = $data['holder_id'];
        $holder = $holderType::find($holderId);
        $amount = $data['amount'];
        $walletType = WalletType::tryFrom($data['wallet_type']);

        $walletType->of($holder)->deposit($amount, WalletTransactionMetaType::DEPOSIT->getUserMeta());

        Notification::make()
            ->title('Deposit successful')
            ->body('You have successfully deposited ' . $amount . ' ' . WalletType::FEST_POINT->getLabel())
            ->success()
            ->send();
    }
}
