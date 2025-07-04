<?php

namespace App\Filament\Wallet\Resources\TransactionResource\Pages;

use App\Filament\Wallet\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
}
