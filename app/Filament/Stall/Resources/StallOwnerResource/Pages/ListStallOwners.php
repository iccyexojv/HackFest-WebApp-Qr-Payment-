<?php

namespace App\Filament\Stall\Resources\StallOwnerResource\Pages;

use App\Filament\Stall\Resources\StallOwnerResource;
use App\Models\StallOwner;
use App\Models\StallStallOwner;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListStallOwners extends ListRecords
{
    protected static string $resource = StallOwnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
             Actions\CreateAction::make(),
            Action::make()
                ->make('addExisting')
                ->form([
                    Hidden::make('stall_id')
                        ->default(Filament::getTenant()->id),
                    Select::make('stall_owner_id')
                        ->required()
                        ->options(
                            function () {
                                $stall = Filament::getTenant();

                                $currentOwnerIds = $stall->stallOwners()->get()->pluck('id');

                                return StallOwner::whereNotIn('id', $currentOwnerIds)
                                    ->pluck('name', 'id');
                            }
                        )
                        ->preload()
                        ->searchable(),
                    Grid::make(2)
                        ->schema([
                            Toggle::make('can_manage_stall_profile')
                                ->default(false),
                            Toggle::make('can_manage_stall_owner')
                                ->default(false),
                        ]),
                ])->action(function (array $data) {
                    try {
                        StallStallOwner::create($data);
                        Notification::make()
                            ->title("Added stall owner")
                            ->color('success')
                            ->body("Successfully added new owner to stall")
                            ->send();
                    } catch (\Throwable $th) {
                        Notification::make()
                            ->title("Failed to add stall owner")
                            ->color('danger')
                            ->body($th->getMessage())
                            ->send();
                    }
                })
                ->slideOver()
            ,
        ];
    }
}
