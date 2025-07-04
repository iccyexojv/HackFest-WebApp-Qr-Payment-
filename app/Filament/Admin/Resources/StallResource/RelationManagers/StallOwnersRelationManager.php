<?php

namespace App\Filament\Admin\Resources\StallResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StallOwnersRelationManager extends RelationManager
{
    protected static string $relationship = 'stallOwners';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Stall Owner Name')
                            ->maxLength(255),
                        TextInput::make('email')
                            ->required()
                            ->label('Email Address')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('contact_number')
                            ->required()
                            ->label('Contact Number')
                            ->tel()
                            ->maxLength(10)
                            ->minLength(10),

                        TextInput::make('password')
                            ->required()
                            ->label('Password')
                            ->password()
                            // ->minLength(8)
                            // ->maxLength(255)
                            ->visibleOn('create'),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('contact_number')
                    ->label('Contact Number'),
            ])
            ->filters([
                //
            ])
                ->headerActions([
                    Tables\Actions\CreateAction::make(),
                    Tables\Actions\AttachAction::make(),
                ])
                ->actions([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DetachAction::make(),
                    Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
