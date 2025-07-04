<?php

namespace App\Filament\Participant\Resources;

use App\Filament\Participant\Resources\ParticipantResource\Pages;
use App\Filament\Participant\Resources\ParticipantResource\RelationManagers;
use App\Models\Participant;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParticipantResource extends Resource
{
    protected static ?string $model = Participant::class;

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
                    ->searchable()
                    ->label('Name'),
                // Tables\Columns\TextColumn::make('college_name')
                //     ->searchable()
                //     ->label('College Name'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()

                    ->label('Email'),

                 Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_number')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
           
            ]);
    }

    public static function getRelations(): array
    {
        return [
        // 
        ];
    }
public static function getEloquentQuery(): Builder
{
    $user = Filament::auth()->user();

    return parent::getEloquentQuery()
        ->where('team_id', $user->team_id);
}
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListParticipants::route('/'),
            // 'create' => Pages\CreateParticipant::route('/create'),
            // 'view' => Pages\ViewParticipant::route('/{record}'),
            // 'edit' => Pages\EditParticipant::route('/{record}/edit'),
        ];
    }
}
