<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\VisitorResource\Pages;
use App\Filament\Admin\Resources\VisitorResource\RelationManagers;
use App\Models\Visitor;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VisitorResource extends Resource
{
    protected static ?string $model = Visitor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Name')
                            ->placeholder('Enter your Name'),
                        TextInput::make('email')
                            ->required()
                            ->email()
                            ->label('Email')
                            ->placeholder('Enter your Email'),
                        TextInput::make('contact_number')
                            // ->required()
                            ->label('contact_number')
                            ->placeholder('Enter your contact Number')
                            ->maxLength(10)
                            ->minLength(10),
                        TextInput::make('address')
                            ->label('Address')
                            ->placeholder('Enter your Address'),
                        // TextInput::make('college_name')
                        //     ->label('College Name')
                        //     ->placeholder('Enter your college name'),

                        TextInput::make('password')
                            ->required(function (string $operation) {
                        if ($operation == 'create') {
                            return true;
                        }
                        return false;
                    })
                    ->dehydrated(function (?string $state, string $operation) {
                        if ($operation == 'create') {
                            return true;
                        } else if (!is_null($state)) {
                            return true;
                        }
                        return false;
                    })
                            ->password()
                            ->revealable()
                            ->label('Password')
                            ->placeholder('Enter your password'),
                    ])
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
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_number')
                    ->sortable()
                    ->searchable(),

                // Tables\Columns\TextColumn::make('college_name')
                //     ->sortable()
                //     ->searchable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVisitors::route('/'),
            'create' => Pages\CreateVisitor::route('/create'),
            'view' => Pages\ViewVisitor::route('/{record}'),
            'edit' => Pages\EditVisitor::route('/{record}/edit'),
        ];
    }
}
