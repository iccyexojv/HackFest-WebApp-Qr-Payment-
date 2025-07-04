<?php

namespace App\Filament\Participant\Resources;

use App\Enums\StallLocation;
use App\Enums\StallType;
use App\Filament\Participant\Resources\StallResource\Pages;
use App\Filament\Participant\Resources\StallResource\RelationManagers;
use App\Models\Stall;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StallResource extends Resource
{
    protected static ?string $model = Stall::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(1)
                    ->schema([
                        // Forms\Components\TextInput::make('name')
                        //     ->required()
                        //     ->label('Stall Name')
                        //     ->maxLength(255),

                        // Forms\Components\Select::make('type')
                        //     ->options(StallType::class)
                        //     ->required()
                        //     ->label('Stall Type'),

                        // Forms\Components\Select::make('location')
                        //     ->options(StallLocation::class)
                        //     ->required()
                        //     ->label('Location'),
                    ]),
            ]);
        }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Sn')
                    ->searchable()
                    ->rowIndex(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Stall Name'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Stall Type'),

                Tables\Columns\TextColumn::make('location'),
            ])
            ->filters([
            //     //
            // ])
            // ->actions([
                // Tables\Actions\ViewAction::make(),
            //     Tables\Actions\EditAction::make(),
            // ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
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
            'index' => Pages\ListStalls::route('/'),
            // 'create' => Pages\CreateStall::route('/create'),
            // 'view' => Pages\ViewStall::route('/{record}'),
            // 'edit' => Pages\EditStall::route('/{record}/edit'),
        ];
    }
}
