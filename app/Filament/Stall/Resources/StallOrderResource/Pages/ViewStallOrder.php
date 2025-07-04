<?php

namespace App\Filament\Stall\Resources\StallOrderResource\Pages;

use App\Enums\StallOrder\StallOrderStatus;
use App\Filament\Stall\Resources\StallOrderResource;
use App\Models\StallOrder;
use App\Models\StallOrderItem;
use App\Wallet\QrHelper;
use App\Wallet\Transaction\StallOrderTransaction;
use chillerlan\QRCode\QRCode;
use Filament\Forms\Components\Placeholder;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Icetalker\FilamentTableRepeatableEntry\Infolists\Components\TableRepeatableEntry;
use Illuminate\Support\HtmlString;
use Nette\Utils\Html;

class ViewStallOrder extends ViewRecord
{
    protected static string $resource = StallOrderResource::class;

    public static function getStallOrderItemsTableRepeater(): TableRepeatableEntry
    {
        return TableRepeatableEntry::make('stallOrderItems')
            ->columnSpanFull()
            ->hiddenLabel()
            ->schema([
                Grid::make(1)
                    ->label("Item")
                    ->schema([
                        TextEntry::make('stallItem.name')
                            ->hiddenLabel(),
                        TextEntry::make('status')
                            ->hiddenLabel()
                            ->badge(),
                    ]),
                // TextEntry::make('unit_price')
                //     ->state(function (StallOrderItem $record) {
                //         return new HtmlString(
                //             $record->fest_point_price . " FP <br> " . $record->game_point_price . " GP"
                //         );
                //     }),
                TextEntry::make('quantity'),
                TextEntry::make('total_price')
                    ->state(function (StallOrderItem $record) {
                        return new HtmlString(
                            $record->fest_point_total_amount . " FP <br> " . $record->game_point_total_amount . " GP"
                        );
                    }),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema(self::getInfolistSchema());
    }

    protected static function getInfolistSchema(): array
    {
        return [
            Grid::make([
                'default' => 1,
                'lg' => 3,
            ])
                ->schema([
                    Section::make('Order Details')
                        ->columnSpan([
                            'default' => 1,
                            'lg' => 2
                        ])
                        ->headerActions([
                            Action::make('complete')
                                ->requiresConfirmation()
                                ->modalHeading('Complete Order')
                                ->modalDescription('Are you sure you want to complete this order?')
                                ->color('success')
                                ->form([
                                    Placeholder::make('note')
                                        ->content(
                                            new HtmlString(
                                                "<p class='border-2 p-2 font-semibold text-justify'>" .
                                                    "Manually completing order means agreeing to condition that the stall owner is responsible for ensuring all items in stall order is completed and delivered." .
                                                    "</p><br>" .
                                                    "<p>" .
                                                    "The general flow of completion is as follows:" .
                                                    "</p>"
                                            )
                                        ),
                                    Placeholder::make('note_contd')
                                        ->hiddenLabel()
                                        ->content(
                                            new HtmlString(
                                                "<img src='" .
                                                    asset('images/manuals/stall/stall-order-completion-flow.png') .
                                                    "'>"
                                            )
                                        )
                                ])
                                ->action(function (StallOrder $record) {
                                    $record->status = StallOrderStatus::COMPLETED;
                                    $record->save();
                                }),
                            Action::make('cancel')
                                ->requiresConfirmation()
                                ->modalHeading('Cancel Order')
                                ->modalDescription("Are you sure you want to cancel this order?")
                                ->color('danger')
                                ->form([
                                    Placeholder::make('note')
                                        ->content(
                                            new HtmlString(
                                                "<p class='border-2 p-2 font-semibold text-justify'>" .
                                                    "Cancelling the whole order means that all amount for this transaction will be refunded to respective payer." .
                                                    "</p>"
                                            ),
                                        ),
                                    Placeholder::make('note_contd')
                                        ->hiddenLabel()
                                        ->content(
                                            new HtmlString(
                                                "<p class='border-2 p-2 font-semibold text-justify'>" .
                                                    "If you only want to cancel single item in the order, please use the 'Cancel Item' button below." .
                                                    "</p>"
                                            ),
                                        ),
                                ])
                                ->action(function (StallOrder $record) {
                                    $record->status = StallOrderStatus::CANCELLED;
                                    $record->save();
                                }),
                        ])
                        ->columns(2)
                        ->schema([
                            TextEntry::make('orderedFor.name')
                                ->columnSpanFull(),
                            TextEntry::make('status')
                                ->badge(),
                            TextEntry::make('wallet_type')
                                ->badge(),
                            TextEntry::make('total_amount')
                                ->state(function (StallOrder $record) {
                                    return $record->getTotalAmount() . " " . $record->wallet_type->getCurrency();
                                }),
                            TextEntry::make('paid_amount')
                                ->state(function (StallOrder $record) {
                                    return $record->getPaidAmount() . " " . $record->wallet_type->getCurrency();
                                }),
                        ]),

                    Section::make()
                        ->columnSpan(1)
                        ->schema([
                            ImageEntry::make('payment_qr')
                                ->hiddenLabel()
                                ->size(200)
                                ->state(function (StallOrder $record) {
                                    $transaction = new StallOrderTransaction($record);
                                    $data = QrHelper::generate($transaction);
                                    return $data;
                                }),

                        ]),
                ]),


            self::getStallOrderItemsTableRepeater(),


        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
            // Action::make('')
        ];
    }
}
