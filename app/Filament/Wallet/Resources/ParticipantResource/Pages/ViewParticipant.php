<?php

namespace App\Filament\Wallet\Resources\ParticipantResource\Pages;

use App\Filament\Wallet\Resources\ParticipantResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewParticipant extends ViewRecord
{
    protected static string $resource = ParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }
}
