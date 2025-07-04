<?php

namespace App\Filament\Wallet\Actions\WalletActions;

use App\Wallet\Enums\WalletTransactionMetaType;
use App\Wallet\Enums\WalletHolderType;
use App\Wallet\Enums\WalletType;
use App\Wallet\WalletExchangeService;
use Bavix\Wallet\Internal\Service\MathService;
use Filament\Facades\Filament;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class TransferAction extends BaseWalletAction
{
    public static function actionName(): string
    {
        return 'transfer';
    }

    public static function form(): array
    {
        return [
            Placeholder::make('note')
                ->content("You can only transfer Fest Point."),
            Fieldset::make('From Wallet')
                ->schema([
                    Select::make('from_holder_type')
                        ->label('Holder Type')
                        ->options(WalletHolderType::class)
                        ->reactive()
                        ->required(),
                    Select::make('from_holder_id')
                        ->label('Holder')
                        ->reactive()
                        ->preload()
                        ->searchable()
                        ->options(function (Get $get) {
                            $holderType = WalletHolderType::tryFrom($get('from_holder_type'));
                            if (is_null($holderType)) {
                                return [];
                            }

                            return self::getHolderOptionsForHolderType($holderType);
                        })
                ]),

            Fieldset::make('To Wallet')
                ->schema([
                    Select::make('to_holder_type')
                        ->label('Holder Type')
                        ->options(WalletHolderType::class)
                        ->reactive()
                        ->required(),
                    Select::make('to_holder_id')
                        ->label('Holder')
                        ->reactive()
                        ->preload()
                        ->searchable()
                        ->options(function (Get $get) {
                            $holderType = WalletHolderType::tryFrom($get('to_holder_type'));
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
            Section::make("Transfer Amount")
                ->schema([
                    Placeholder::make('transfer_amount')
                        ->hiddenLabel()
                        ->content(function (Get $get) {
                            $amount = $get('amount') ?? 0;
                            return $amount . " " . WalletType::FEST_POINT->getLabel();
                        }),
                ]),
            Section::make("From Wallet")
                ->schema([
                    Placeholder::make('from_wallet')
                        ->hiddenLabel()
                        ->content(function (Get $get) {

                            $fromWalletHolderType = WalletHolderType::tryFrom($get('from_holder_type'));
                            $fromWalletHolderId = $get('from_holder_id');

                            if (
                                is_null($fromWalletHolderType) ||
                                is_null($fromWalletHolderId)
                            ) {
                                return 'Unknown';
                            }

                            $fromWallet = $fromWalletHolderType->getHolderClass()::find($fromWalletHolderId)?->getWallet(WalletType::FEST_POINT->value);


                            if (
                                is_null($fromWallet)
                            ) {
                                return;
                            }

                            $summary = "
                                    Wallet Id: " . $fromWallet->uuid . " <br>
                                    Holder Type: " . $fromWalletHolderType->getLabel() . " <br>
                                    Holder Id: " . $fromWalletHolderId . " <br>
                                    ";

                            $toWallet = $fromWalletHolderType->getHolderClass()::find($fromWalletHolderId)?->getWallet(WalletType::FEST_POINT->value);

                            if (
                                is_null($toWallet)
                            ) {
                                return "Unknown";
                            }

                            $fromWalletHolderName = $fromWallet->holder()->first()->name;


                            $summary = "Wallet Id: " . $toWallet->uuid . " <br>";
                            $summary .= "Holder Type: " . $fromWalletHolderType->getLabel() . " <br>";
                            $summary .= "Holder Id: " . $fromWalletHolderId . " <br>";
                            $summary .= "Holder Name: " . $fromWalletHolderName;

                            return new HtmlString($summary);
                        }),
                ]),

            Section::make("To Wallet")
                ->schema([
                    Placeholder::make('to_wallet')
                        ->hiddenLabel()
                        ->content(function (Get $get) {

                            $toWalletHolderType = WalletHolderType::tryFrom($get('to_holder_type'));
                            $toWalletHolderId = $get('to_holder_id');

                            if (
                                is_null($toWalletHolderType) ||
                                is_null($toWalletHolderId)
                            ) {
                                return 'Unknown';
                            }

                            $toWallet = $toWalletHolderType->getHolderClass()::find($toWalletHolderId)?->getWallet(WalletType::FEST_POINT->value);

                            if (
                                is_null($toWallet)
                            ) {
                                return "Unknown";
                            }

                            $toWalletHolderName = $toWallet->holder()->first()->name;


                            $summary = "Wallet Id: " . $toWallet->uuid . " <br>";
                            $summary .= "Holder Type: " . $toWalletHolderType->getLabel() . " <br>";
                            $summary .= "Holder Id: " . $toWalletHolderId . " <br>";
                            $summary .= "Holder Name: " . $toWalletHolderName;

                            return new HtmlString($summary);
                        }),
                ]),

            static::getProcessedByField(),
        ];
    }

    public static function action(array $data): void
    {
        $amount = $data['amount'];
        $fromHolderType = $data['from_holder_type'];
        $fromHolderId = $data['from_holder_id'];
        $fromWalletHolderType = WalletHolderType::tryFrom($fromHolderType);
        $fromWalletHolder = $fromWalletHolderType->getHolderClass()::find($fromHolderId);
        $fromWallet = WalletType::FEST_POINT->of($fromWalletHolder);

        $toHolderType = $data['to_holder_type'];
        $toHolderId = $data['to_holder_id'];
        $toWalletHolderType = WalletHolderType::tryFrom($toHolderType);
        $toWalletHolder = $toWalletHolderType->getHolderClass()::find($toHolderId);
        $toWallet = WalletType::FEST_POINT->of($toWalletHolder);

        if (
            is_null($fromWallet) ||
            is_null($toWallet)
        ) {
            Notification::make()
                ->title('Transfer failed')
                ->body('Wallet does not exist.')
                ->danger()
                ->send();

            return;
        }

        if ($fromWallet?->balance < $amount) {
            Notification::make()
                ->title('Transfer failed')
                ->body('Wallet does not have sufficient balance.')
                ->danger()
                ->send();

            return;
        }

        $fromWallet->transfer($toWallet, $amount, WalletTransactionMetaType::TRANSFER->getUserMeta());

        Notification::make()
            ->title('Transfer successful')
            ->body('You have successfully transferred ' . $amount . ' FP from ' . $fromWallet->uuid  . ' to ' . $toWallet->uuid)
            ->success()
            ->send();
    }
}
