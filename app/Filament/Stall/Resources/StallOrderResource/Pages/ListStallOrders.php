<?php

namespace App\Filament\Stall\Resources\StallOrderResource\Pages;

use App\Filament\Stall\Resources\StallOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStallOrders extends ListRecords
{
    protected static string $resource = StallOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
