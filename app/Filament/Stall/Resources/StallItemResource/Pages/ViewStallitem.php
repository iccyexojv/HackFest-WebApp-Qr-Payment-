<?php

namespace App\Filament\Stall\Resources\StallItemResource\Pages;

use App\Filament\Stall\Resources\StallItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStallitem extends ViewRecord
{
    protected static string $resource = StallItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
