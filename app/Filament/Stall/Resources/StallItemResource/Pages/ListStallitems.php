<?php

namespace App\Filament\Stall\Resources\StallItemResource\Pages;

use App\Filament\Stall\Resources\StallItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStallitems extends ListRecords
{
    protected static string $resource = StallItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
         Actions\CreateAction::make(),
        ];
    }
}
