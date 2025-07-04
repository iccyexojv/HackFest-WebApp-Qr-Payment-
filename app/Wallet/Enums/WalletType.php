<?php

namespace App\Wallet\Enums;

use App\Wallet\BaseAuthenticatableWalletHolder;
use App\Wallet\BaseModelWalletHolder;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Models\Wallet as WalletModel;
use Filament\Support\Contracts\HasLabel;

enum WalletType: string implements HasLabel
{
    case FEST_POINT = 'fest-point';
    case GAME_POINT = 'game-point';
    case KBC_POINT = 'kbc-point';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::FEST_POINT => 'Fest Point',
            self::GAME_POINT => 'Game Point',
            self::KBC_POINT => 'KBC Point',
        };
    }

    public function getCurrency(): string
    {
        return match ($this) {
            self::FEST_POINT => 'FP',
            self::GAME_POINT => 'GP',
            self::KBC_POINT => 'KP',
        };
    }

    public function createWallet(
        BaseModelWalletHolder|BaseAuthenticatableWalletHolder $baseWalletHolder,
        float $initialBalance = 0,
        float $creditLimit = 0
    ): Wallet {
        $wallet = $baseWalletHolder->createWallet([
            'name' => $this->getLabel(),
            'slug' => $this->value,
            'meta' => [
                'currency' => $this->getCurrency(),
                'credit' => $creditLimit,
            ],
        ]);

        if ($initialBalance > 0) {
            $wallet->deposit($initialBalance, WalletTransactionMetaType::INITIAL_BALANCE->getSystemMeta());
        }

        return $wallet;
    }

    public function of(BaseModelWalletHolder|BaseAuthenticatableWalletHolder|null $walletHolder): ?WalletModel
    {
        return $walletHolder?->getWallet($this->value);
    }
}
