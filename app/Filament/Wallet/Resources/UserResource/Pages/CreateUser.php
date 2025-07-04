<?php

namespace App\Filament\Wallet\Resources\UserResource\Pages;

use App\Filament\Wallet\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
