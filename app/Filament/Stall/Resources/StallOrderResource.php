<?php

namespace App\Filament\Stall\Resources;

use App\Filament\Stall\Resources\StallOrderResource\Pages;
use App\Filament\Stall\Resources\StallOrderResource\RelationManagers;
use App\Filament\Stall\Resources\StallOrderResource\RelationManagers\StallOrderTransactionsRelationManager;
use App\Models\Participant;
use App\Models\StallOrder;
use App\Models\User;
use App\Models\Visitor;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class StallOrderResource extends Resource
{
    protected static ?string $model = StallOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sn')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('ordered_for')
                    ->state(function(StallOrder $order){
                        $order->loadMissing('orderedFor');
                        return $order->ordered_for->name;
                    })
                    ->label('Ordered for'),
                Tables\Columns\TextColumn::make('fest_point_total_amount')
                    ->label('Fest Point Price')
                    ->sortable(),

                Tables\Columns\TextColumn::make('game_point_total_amount')
                    ->label('Game Point Price')
                    ->sortable(),
                Tables\Columns\TextColumn::make('wallet_type'),



            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            StallOrderTransactionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStallOrders::route('/'),
            'create' => Pages\CreateStallOrder::route('/create'),
            'view' => Pages\ViewStallOrder::route('/{record}'),
            'edit' => Pages\EditStallOrder::route('/{record}/edit'),
        ];
    }
}
