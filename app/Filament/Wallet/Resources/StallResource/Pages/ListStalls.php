<?php

namespace App\Filament\Wallet\Resources\StallResource\Pages;

use App\Filament\Wallet\Resources\StallResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStalls extends ListRecords
{
    protected static string $resource = StallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
