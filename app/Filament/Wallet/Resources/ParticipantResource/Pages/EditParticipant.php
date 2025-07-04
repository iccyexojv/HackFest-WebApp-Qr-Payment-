<?php

namespace App\Filament\Wallet\Resources\ParticipantResource\Pages;

use App\Filament\Wallet\Resources\ParticipantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditParticipant extends EditRecord
{
    protected static string $resource = ParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
}
