<?php

namespace App\Filament\Admin\Resources\StallResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StallItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'stallItems';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Name'),
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3),
                        TextInput::make('fest_point_price')
                            ->required()
                            ->label('Fest Point Price')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(10000),
                        TextInput::make('game_point_price')
                            ->required()
                            ->label('Game Point Price')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(10000),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->label('ID'),
                Tables\Columns\TextColumn::make('stall.name')
                    ->sortable()
                    ->searchable()
                    ->label('Stall'),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Name'),
                Tables\Columns\TextColumn::make('fest_point_price')
                    ->sortable()
                    ->searchable()
                    ->label('Fest Point Price'),
                Tables\Columns\TextColumn::make('game_point_price')
                    ->sortable()
                    ->searchable()
                    ->label('Game Point Price'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Created At'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AssociateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DissociateAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
