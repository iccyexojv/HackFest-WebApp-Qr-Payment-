<?php

namespace App\Filament\Visitor\Resources;

use App\Enums\StallLocation;
use App\Enums\StallType;
use App\Filament\Visitor\Resources\StallResource\Pages;
use App\Filament\Visitor\Resources\StallResource\RelationManagers\StallItemsRelationManager;
use App\Models\Stall;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StallResource extends Resource
{
    protected static ?string $model = Stall::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Stall Name')
                    ->maxLength(255),

                Forms\Components\Select::make('type')
                    ->options(StallType::class)
                    ->required()
                    ->label('Stall Type'),

                 Forms\Components\Select::make('location')
                            ->options(StallLocation::class)
                            ->required()
                            ->label('Location'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Stall Name'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Stall Type'),

                Tables\Columns\TextColumn::make('location'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), 
            ])
            ->bulkActions([]); 
    }

    public static function getRelations(): array
    {
        return [
            
            StallItemsRelationManager   ::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStalls::route('/'),
            'view' => Pages\ViewStall::route('/{record}'),
        ];
    }
}
