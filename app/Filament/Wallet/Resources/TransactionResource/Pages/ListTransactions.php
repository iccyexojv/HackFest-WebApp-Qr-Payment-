<?php

namespace App\Filament\Wallet\Resources\TransactionResource\Pages;

use App\Filament\Wallet\Actions\WalletActions\ConvertAction;
use App\Filament\Wallet\Actions\WalletActions\DepositAction;
use App\Filament\Wallet\Actions\WalletActions\TransferAction;
use App\Filament\Wallet\Actions\WalletActions\WithdrawAction;
use App\Filament\Wallet\Resources\TransactionResource;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),

            DepositAction::make(),
            WithdrawAction::make(),
            ConvertAction::make(),
            TransferAction::make(),
        ];
    }
}
