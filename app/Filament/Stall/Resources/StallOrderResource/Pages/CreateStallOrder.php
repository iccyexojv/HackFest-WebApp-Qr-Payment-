<?php

namespace App\Filament\Stall\Resources\StallOrderResource\Pages;

use App\Enums\StallOrder\StallOrderItemStatus;
use App\Enums\StallOrder\StallOrderStatus;
use App\Models\User;
use Filament\Actions;
use App\Models\Visitor;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\StallItem;
use App\Models\Participant;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Stall\Resources\StallOrderResource;
use App\Wallet\Enums\WalletType;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Filament\Support\Enums\Alignment;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Livewire\Component as Livewire;
use Nette\Utils\Html;

class CreateStallOrder extends CreateRecord
{
    use HasWizard;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    protected static string $resource = StallOrderResource::class;

    protected function getSteps(): array
    {
        return [
            self::getOrderItemsStep(),
            self::getOrderSummaryStep(),
        ];
    }

    protected static function getHiddenFields(): array
    {
        return [
            Hidden::make('stall_id')
                ->default(Filament::getTenant()->id),
            Hidden::make('stall_owner_id')
                ->default(Filament::auth()->id()),

            Hidden::make('status')
                ->default(StallOrderStatus::PENDING_PAYMENT->value),

            Hidden::make('fest_point_total_amount'),
            Hidden::make('game_point_total_amount'),

        ];
    }

    protected static function getOrderForFieldset(): Fieldset
    {
        return Fieldset::make('ordered_for')
            ->hiddenLabel()
            ->schema([
                Select::make('ordered_for_type')
                    ->native(false)
                    ->reactive()
                    ->options([
                        User::class => "Organizer",
                        Participant::class => "Participant",
                        Visitor::class => "Visitor",
                    ]),
                Select::make('ordered_for_id')
                    ->native(false)
                    ->options(function (Get $get) {
                        $orderedForType = $get('ordered_for_type');
                        if (is_null($orderedForType)) {
                            return [];
                        }
                        return $orderedForType::query()->pluck('name', 'id');
                    })
                    ->preload()
                    ->searchable(),
                RadioDeck::make('wallet_type')
                    ->color('primary')
                    ->columnSpanFull()
                    ->required()
                    ->columns(2)
                    ->options([
                        WalletType::FEST_POINT->value => "Fest Point",
                        WalletType::GAME_POINT->value => "Game Point",
                    ])
            ]);
    }

    protected static function getOrderSummaryTableRepeater(string $name, bool $dehydated = true): TableRepeater
    {
        return TableRepeater::make($name)
            ->hiddenLabel()
            ->columnSpanFull()
            // ->relationship($relationship ? 'stallOrderItems' : null)
            ->columns(2)
            ->default([])
            ->minItems(1)
            ->addable(false)
            ->reorderable(false)
            ->cloneable(false)
            // ->dehydrated($dehydated)
            ->schema([
                Hidden::make('stall_id')
                    ->default(Filament::getTenant()->id),
                Hidden::make('stall_owner_id')
                    ->default(Filament::auth()->id()),
                Hidden::make('stall_item_id'),
                Hidden::make('fest_point_price'),
                Hidden::make('game_point_price'),
                Hidden::make('quantity'),
                Hidden::make('fest_point_total_amount'),
                Hidden::make('game_point_total_amount'),
                Hidden::make('status'),

                Placeholder::make('item')
                    ->dehydrated(false)
                    ->content(function (?string $state) {
                        return new HtmlString($state ?? '-');
                    }),

                Placeholder::make('unit_price')
                    ->dehydrated(false)
                    ->content(function (?string $state) {
                        return new HtmlString($state ?? '- FP | - GP');
                    }),
                Placeholder::make('total_price')
                    ->dehydrated(false)
                    ->content(function (?string $state) {
                        return new HtmlString($state ?? '- FP | - GP');
                    }),

            ]);
    }

    protected static function getOrderItemsStep(): Step
    {
        return Step::make("New Order")
            ->schema([
                ...self::getHiddenFields(),

                Section::make()
                    ->compact()
                    ->key('order_items_section')
                    ->columns(2)
                    ->footerActionsAlignment(Alignment::End)
                    ->footerActions([
                        Action::make('addItem')
                            // ->align(Alignment::End)
                            ->action(function (Get $get, Set $set, Livewire $livewire, Component $component) {
                                $quantity = $get('quantity');
                                $stallItemId = $get('stall_item_id');

                                $stallItem = StallItem::find($stallItemId);

                                $stallOrderItems = collect($get('stallOrderItems') ?? []);

                                $stallOrderItems->put(Str::uuid()->toString(), [
                                    'stall_id' => Filament::getTenant()->id,
                                    'stall_item_id' => $stallItemId,
                                    'quantity' => $quantity,
                                    'fest_point_price' => $stallItem->fest_point_price,
                                    'game_point_price' => $stallItem->game_point_price,
                                    'fest_point_total_amount' => $stallItem->fest_point_price * $quantity,
                                    'game_point_total_amount' => $stallItem->game_point_price * $quantity,
                                    'item' => $stallItem->name . ' x ' . $quantity,
                                    'unit_price' => $stallItem->fest_point_price . ' FP | ' . $stallItem->game_point_price . ' GP',
                                    'total_price' => $stallItem->fest_point_price * $quantity . ' FP | ' . $stallItem->game_point_price * $quantity . ' GP',
                                    'status' => StallOrderItemStatus::PENDING,
                                ]);
                                $set('stallOrderItems', $stallOrderItems->toArray());
                                $set('stall_item_id', null);
                                $set('quantity', 1);
                            }),
                    ])
                    ->schema([
                        Select::make('stall_item_id')
                            ->native(false)
                            ->dehydrated(false)
                            ->label("Item")
                            ->reactive()
                            ->options(
                                Filament::getTenant()
                                    ?->stallItems()
                                    ->get()
                                    ->pluck('name', 'id')
                            ),
                        TextInput::make('quantity')
                            ->numeric()
                            ->dehydrated(false)
                            ->reactive()
                            ->default(1),
                    ]),

                self::getOrderSummaryTableRepeater('stallOrderItems')
                            ->relationship('stallOrderItems'),

            ])
            ->afterValidation(function (Get $get, Set $set) {
                $stallOrderItems = collect($get('stallOrderItems'));


                $stallItems = StallItem::whereIn('id', $stallOrderItems->pluck('stall_item_id'))->get();

                // dd($stallOrderItems, $stallItems);


                $orderSummaryCollection = collect();

                $totalAmount = [
                    'fp' => 0,
                    'gp' => 0,
                ];

                $amounts = $stallOrderItems
                    ->map(function ($stallOrderItem) use ($stallItems, $orderSummaryCollection, $totalAmount) {
                        if (is_null($stallOrderItem['stall_item_id'])) {
                            return $stallItems;
                        }
                        $stallItem =  $stallItems->where('id', $stallOrderItem['stall_item_id'])->first();
                        $quantity = $stallOrderItem['quantity'];

                        $totalAmount['fp'] = (int) $totalAmount['fp'] + ((int)$stallItem->fest_point_price * (int) $quantity);
                        $totalAmount['gp'] = (int) $totalAmount['gp'] + ((int)$stallItem->game_point_price * (int) $quantity);


                        $orderSummaryCollection->put(Str::uuid()->toString(), $stallOrderItem);

                        return [
                            'fp' => $stallItem->fest_point_price * $quantity,
                            'gp' => $stallItem->game_point_price * $quantity,
                        ];
                    });

                $totalAmount['fp'] = (int) $totalAmount['fp'] + (int) $amounts->sum('fp');
                $totalAmount['gp'] = (int) $totalAmount['gp'] + (int) $amounts->sum('gp');

                $totalAmountContent = '
                <p class="w-full text-right text-2xl">Total: ' .
                    $totalAmount['fp'] . " FP | " . $totalAmount['gp'] . " GP" .
                    "</p>";

                $set('order_summary', $orderSummaryCollection->toArray());
                $set('total_amount', $totalAmountContent);
                $set('fest_point_total_amount', $totalAmount['fp']);
                $set('game_point_total_amount', $totalAmount['gp']);
            });
    }

    protected static function getOrderSummaryStep(): Step
    {
        return Step::make("Summary")
            ->columns(1)
            ->schema([
                self::getOrderSummaryTableRepeater('order_summary', false)
                    ->deletable(false)
                    ->dehydrated(false),
                Placeholder::make('total_amount')
                    ->hiddenLabel()
                    ->content(function (?string $state) {
                        return new HtmlString($state);
                    }),



                self::getOrderForFieldset(),
            ]);
    }
}
