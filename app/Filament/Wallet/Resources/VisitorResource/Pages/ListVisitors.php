<?php

namespace App\Filament\Wallet\Resources\VisitorResource\Pages;

use App\Filament\Wallet\Resources\VisitorResource;
use Filament\Resources\Pages\ListRecords;

class ListVisitors extends ListRecords
{
    protected static string $resource = VisitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),


        ];
    }
}
