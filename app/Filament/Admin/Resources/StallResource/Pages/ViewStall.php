<?php

namespace App\Filament\Admin\Resources\StallResource\Pages;

use App\Filament\Admin\Resources\StallResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStall extends ViewRecord
{
    protected static string $resource = StallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
