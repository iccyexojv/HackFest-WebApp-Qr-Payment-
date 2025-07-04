<?php

namespace App\Filament\Wallet\Resources;

use App\Filament\Wallet\Resources\TransactionResource\Pages;
use App\Filament\Wallet\Resources\TransactionResource\RelationManagers;
use Bavix\Wallet\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Fieldset::make('Payable')
                    ->schema([
                        TextEntry::make('payable_type')
                            ->formatStateUsing(function (?string $state) {
                                return last(explode('\\', $state));
                            })
                            ->label('Type'),
                        TextEntry::make('payable.name')
                            ->label('Name'),
                    ]),
                FieldSet::make('Wallet')
                    ->schema([
                        TextEntry::make('wallet.uuid')
                            ->label('ID'),
                        TextEntry::make('wallet.name')
                            ->label('Name'),
                    ]),
                FieldSet::make('Transaction')
                    ->schema([
                        TextEntry::make('uuid'),
                        TextEntry::make('type'),
                        TextEntry::make('amount'),
                        IconEntry::make('confirmed')
                            ->boolean(),
                        KeyValueEntry::make('meta')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            // 'create' => Pages\CreateTransaction::route('/create'),
            'view' => Pages\ViewTransaction::route('/{record}'),
            // 'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
