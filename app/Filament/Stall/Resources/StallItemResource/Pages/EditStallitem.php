<?php

namespace App\Filament\Stall\Resources\StallItemResource\Pages;

use App\Filament\Stall\Resources\StallItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStallitem extends EditRecord
{
    protected static string $resource = StallItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
