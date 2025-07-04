<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\WalletsOverviewWidget;
use App\Models\StallOwner;
use Bavix\Wallet\Models\Transaction;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class TransactionHistory extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.transaction-history';

    protected static ?string $navigationGroup = "History";

    public function table(Table $table): Table
    {
        $payable = Filament::auth()->user();
        if (get_class($payable) == StallOwner::class) {
            $payable = Filament::getTenant();
        }

        // dd($payable);

        return $table
            ->query(
                Transaction::where('payable_type', get_class($payable))
                    ->where('payable_id', $payable->id)
            )
            ->columns([
                TextColumn::make('uuid'),
                TextColumn::make('payable.name'),
                TextColumn::make('wallet.name'),
                TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'success' => 'deposit',
                        'danger' => 'withdraw',
                    ]),
                TextColumn::make('amount'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            WalletsOverviewWidget::class,
        ];
    }
}
