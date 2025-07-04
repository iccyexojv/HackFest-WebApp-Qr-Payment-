<?php

namespace App\Filament\Wallet\Resources\VisitorResource\Pages;

use App\Filament\Wallet\Resources\VisitorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVisitor extends EditRecord
{
    protected static string $resource = VisitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
}
