<?php

namespace App\Filament\Visitor\Resources\StallResource\Pages;

use App\Filament\Visitor\Resources\StallResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStall extends EditRecord
{
    protected static string $resource = StallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
}
