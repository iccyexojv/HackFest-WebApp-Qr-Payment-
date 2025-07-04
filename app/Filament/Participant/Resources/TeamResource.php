<?php

namespace App\Filament\Participant\Resources;

use App\Filament\Participant\Resources\TeamResource\Pages;
use App\Filament\Participant\Resources\TeamResource\RelationManagers;
use App\Filament\Admin\Resources\TeamResource\RelationManagers\ParticipantRelationManager;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('college_name')
                    ->sortable()
                    ->searchable(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->label('Created At')
                //     ->dateTime()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->label('Updated At')
                //     ->dateTime()
                //     ->sortable(),

            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query();
            // ->where('team_id', Filament::auth()->user()->team_id);
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
            'index' => Pages\ListTeams::route('/'),
            // 'create' => Pages\CreateTeam::route('/create'),
            // 'view' => Pages\ViewTeam::route('/{record}'),
            // 'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}
