<?php

namespace App\Filament\Stall\Resources;

use App\Filament\Stall\Resources\StallItemResource\Pages;
use App\Filament\Stall\Resources\StallItemResource\RelationManagers;
use App\Models\StallItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StallItemResource extends Resource
{
    protected static ?string $model = StallItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([


                Forms\Components\TextInput::make('name')
                    ->label('Item Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->maxLength(500)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('fest_point_price')
                    ->label('Fest Point Price')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('game_point_price')
                    ->label('Game Point Price')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sn')
                     ->rowIndex(),

                // Tables\Columns\TextColumn::make('stall.name')
                //     ->label('Stall Name')
                //     ->sortable()
                //     ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Item Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('fest_point_price')
                    ->label('Fest Point Price')
                    ->sortable(),

                Tables\Columns\TextColumn::make('game_point_price')
                    ->label('Game Point Price')
                    ->sortable(),
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

    protected static ?string $tenantOwnershipRelationshipName = 'stall';
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStallitems::route('/'),
            'create' => Pages\CreateStallitem::route('/create'),
            'view' => Pages\ViewStallitem::route('/{record}'),
            'edit' => Pages\EditStallitem::route('/{record}/edit'),
        ];
    }



}
