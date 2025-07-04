<?php

namespace App\Filament\Stall\Resources\StallOwnerResource\Pages;

use App\Filament\Stall\Resources\StallOwnerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStallOwner extends EditRecord
{
    protected static string $resource = StallOwnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
             Actions\DeleteAction::make(),
        ];
    }
}
