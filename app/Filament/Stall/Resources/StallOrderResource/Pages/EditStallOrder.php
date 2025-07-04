<?php

namespace App\Filament\Stall\Resources\StallOrderResource\Pages;

use App\Filament\Stall\Resources\StallOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStallOrder extends EditRecord
{
    protected static string $resource = StallOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
