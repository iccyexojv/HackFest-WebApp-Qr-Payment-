<?php

namespace App\Filament\Stall\Resources\StallOwnerResource\Pages;

use App\Filament\Stall\Resources\StallOwnerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStallOwner extends ViewRecord
{
    protected static string $resource = StallOwnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
         Actions\EditAction::make(),
        ];
    }
}
