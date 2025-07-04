<?php

namespace App\Wallet\Contracts;

use App\Wallet\Enums\WalletHolderType;
use Bavix\Wallet\Interfaces\Wallet;

interface WalletHolderContract extends Wallet
{
    public function getWalletHolderType(): WalletHolderType;

    public function createWallet(array $data): Wallet;
}
