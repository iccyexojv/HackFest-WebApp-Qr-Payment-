<?php

namespace App\Filament\Stall\Resources;

use App\Filament\Admin\Resources\StallResource\RelationManagers\StallItemsRelationManager;
use App\Filament\Admin\Resources\StallResource\RelationManagers\StallOwnersRelationManager;
use App\Filament\Stall\Resources\StallOwnerResource\Pages;
use App\Filament\Stall\Resources\StallOwnerResource\RelationManagers;
use App\Models\Stall;
use App\Models\StallOwner;
use App\Models\StallStallOwner;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class StallOwnerResource extends Resource
{
    protected static ?string $model = StallOwner::class;
    public static ?string $tenantOwnershipRelationshipName = 'stalls';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Stall Owner Name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->label('Email Address')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_number')
                    ->required()
                    ->label('Contact Number')
                    ->tel()
                    ->maxLength(10)
                    ->minLength(10),
                Forms\Components\TextInput::make('password')
                    ->required()
                    ->label('Password')
                    ->password()
                    // ->minLength(8)
                    // ->maxLength(255)
                    ->visibleOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('contact_number')
                    ->label('Contact Number'),

            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->slideOver()
                    ->requiresConfirmation()
                    ->modalHeading("Edit stall owner")
                    ->form(function (StallOwner $record) {
                        $stall = Filament::getTenant();
                        $stallStallOwner = StallStallOwner::where("stall_id", $stall->id)
                            ->where('stall_owner_id', $record->id)
                            ->first();
                        return [
                            Toggle::make('can_manage_stall_profile')
                                ->default($stallStallOwner?->can_manage_stall_profile),
                            Toggle::make('can_manage_stall_owner')
                                ->default($stallStallOwner?->can_manage_stall_owner),
                        ];
                    })->action(function (StallOwner $record, array $data) {
                        $stall = Filament::getTenant();
                        $updated = StallStallOwner::where('stall_owner_id', $stall->id)
                            ->where('stall_owner_id', $record->id)
                            ->update($data);
                        if (!$updated) {
                            Notification::make()
                                ->title("Stall owner updated")
                                ->body("Stall owner updated successfully for stall")
                                ->success();
                        } else {
                            Notification::make()
                                ->title("Failed to update stall owner")
                                ->body("Stall owner could not be updated for stall")
                                ->danger();
                        }
                    }),
                Tables\Actions\Action::make('remove')
                    ->requiresConfirmation()
                    ->modalHeading("Remove stall owner")
                    ->modalDescription("Do you want to remove owner from stall?")
                    ->action(function (StallOwner $record) {
                        $stall = Filament::getTenant();
                        $deleted = StallStallOwner::where('stall_owner_id', $record->id)
                            ->where('stall_id', $stall->id)
                            ->delete();
                        if (!$deleted) {
                            Notification::make()
                                ->title("Stall owner removed")
                                ->body("Stall owner removed successfully from stall")
                                ->success();
                        } else {
                            Notification::make()
                                ->title("Failed to remove stall owner")
                                ->body("Stall owner could not be removed from stall")
                                ->danger();
                        }
                    })
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                // Tables\Actions\
            ]);
    }

    public static function getRelations(): array
    {
        return [
            StallOwnersRelationManager::class,
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStallOwners::route('/'),
            'create' => Pages\CreateStallOwner::route('/create'),
            // 'view' => Pages\ViewStallOwner::route('/{record}'),
            // 'edit' => Pages\EditStallOwner::route('/{record}/edit'),
        ];
    }
}
