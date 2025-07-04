<?php

namespace App\Filament\Stall\Resources\StallOwnerResource\Pages;

use App\Filament\Stall\Resources\StallOwnerResource;
use App\Models\StallStallOwner;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateStallOwner extends CreateRecord
{
    protected static string $resource = StallOwnerResource::class;

    protected function afterCreate(): void
    {
        $stallOwner = $this->getRecord();
        $stall = Filament::getTenant();

        StallStallOwner::create([
            'stall_id' => $stall->id,
            'stall_owner_id' => $stallOwner->id,
        ]);

    }
}
