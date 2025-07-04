<?php

namespace App\Filament\Wallet\Actions\WalletActions;

use Filament\Forms\Get;
use Filament\Facades\Filament;
use App\Wallet\Enums\WalletType;
use Illuminate\Support\HtmlString;
use App\Wallet\WalletExchangeService;
use Filament\Forms\Components\Select;
use App\Wallet\Enums\WalletHolderType;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Bavix\Wallet\Internal\Service\MathService;
use App\Filament\Wallet\Actions\WalletActions\BaseWalletAction;
use App\Wallet\Enums\WalletTransactionMetaType;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ConvertAction extends BaseWalletAction
{
    public static function actionName(): string
    {
        return 'convert';
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

            FieldSet::make("Conversion")
                ->schema([
                    Select::make('from_type')
                        ->options(
                            options: collect(WalletType::cases())
                                ->filter(fn(WalletType $type) => $type != WalletType::KBC_POINT)
                                ->mapWithKeys(function (WalletType $type) {
                                    return [$type->value => $type->getLabel()];
                                })
                                ->toArray()
                        )
                        ->reactive()
                        ->required(),

                    Select::make('to_type')
                        ->options(
                            collect(WalletType::cases())
                                ->filter(fn(WalletType $type) => $type != WalletType::KBC_POINT)
                                ->mapWithKeys(function (WalletType $type) {
                                    return [$type->value => $type->getLabel()];
                                })
                                ->toArray()
                        )
                        ->reactive()
                        ->required(),
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
            Section::make("Conversion")
                ->schema([
                    Placeholder::make('conversion_amount')
                        ->hiddenLabel()
                        ->content(function (Get $get) {
                            $amount = $get('amount') ?? 0;

                            $fromWalletType = WalletType::tryFrom($get('from_type'));
                            $toWalletType = WalletType::tryFrom($get('to_type'));

                            if (
                                is_null($fromWalletType) ||
                                is_null($toWalletType)
                            ) {
                                return "Unknown";
                            }


                            $exchangeService = new WalletExchangeService(
                                new MathService(2)
                            );

                            $convertedAmount = $exchangeService->convertTo(
                                $fromWalletType->getCurrency(),
                                $toWalletType->getCurrency(),
                                $amount
                            );

                            $content = "From: " . $amount . " " . $fromWalletType->getLabel() . "<br>";
                            $content .= "To: " . $convertedAmount . " " . $toWalletType->getLabel();

                            return new HtmlString($content);
                        }),
                ]),
            Section::make("Holder")
                ->schema([
                    Placeholder::make('holder')
                        ->hiddenLabel()
                        ->content(function (Get $get) {

                            $receiverId = $get('holder_id');
                            $receiverType = WalletHolderType::tryFrom($get('holder_type'));

                            if (
                                is_null($receiverId) ||
                                is_null($receiverType)
                            ) {
                                return "Unknown";
                            }

                            $reveiver = $receiverType->getHolderClass()::find($receiverId);

                            $summary = "Id: " . $receiverId . "<br>";
                            $summary .= "Type: " . $receiverType->getLabel() . "<br>";
                            $summary .= "Name: " . $reveiver->name;

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

        $fromWalletType = WalletType::tryFrom($data['from_type']);
        $fromWallet = $fromWalletType?->of($holder);
        $toWalletType = WalletType::tryFrom($data['to_type']);
        $toWallet = $toWalletType?->of($holder);

        $exchangeService = new WalletExchangeService(
            new MathService(2)
        );

        if ($fromWallet->balance < $amount) {
            Notification::make()
                ->title('Conversion failed')
                ->body('Wallet does not have sufficient balance.')
                ->danger()
                ->send();

            return;
        }

        $convertedAmount = $exchangeService->convertTo(
            $fromWalletType->getCurrency(),
            $toWalletType->getCurrency(),
            $amount
        );

        $fromWallet->exchange($toWallet, $amount, WalletTransactionMetaType::CONVERT->getUserMeta());

        Notification::make()
            ->title('Conversion successful')
            ->body('You have successfully converted ' . $amount . ' ' . $fromWalletType->getLabel() . ' to ' . $convertedAmount . ' ' . $toWalletType->getLabel())
            ->success()
            ->send();
    }
}
