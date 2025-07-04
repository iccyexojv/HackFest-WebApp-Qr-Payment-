<?php

namespace App\Filament\Wallet\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Stall;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Wallet\Resources\StallResource\Pages;
use App\Filament\Wallet\Resources\StallResource\RelationManagers;
use App\Filament\Wallet\Resources\StallResource\RelationManagers\WalletsRelationManager;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables\Columns\TextColumn;

class StallResource extends Resource
{
    protected static ?string $model = Stall::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = "Wallet Holder";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                TextInput::make('type'),
                TextInput::make('location'),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('type'),
                TextEntry::make('location'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('type'),
                TextColumn::make('location'),
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
            WalletsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStalls::route('/'),
            // 'create' => Pages\CreateStall::route('/create'),
            'view' => Pages\ViewStall::route('/{record}'),
            // 'edit' => Pages\EditStall::route('/{record}/edit'),
        ];
    }
}
