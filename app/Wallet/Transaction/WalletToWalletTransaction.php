<?php

namespace App\Wallet\Transaction;

use App\Wallet\BaseAuthenticatableWalletHolder;
use App\Wallet\BaseModelWalletHolder;
use App\Wallet\Enums\WalletTransactionMetaType;
use App\Wallet\Enums\WalletTransactionType;
use App\Wallet\Enums\WalletType;
use Bavix\Wallet\Models\Transaction;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Database\Eloquent\Model;

class WalletToWalletTransaction extends BaseTransaction
{
    public function __construct(
        protected BaseModelWalletHolder|BaseAuthenticatableWalletHolder $walletHolder,
        protected WalletType $walletType,
    ) {}


    // abstract methods implementation

    public function getWalletTransactionType(): WalletTransactionType
    {
        return WalletTransactionType::WALLET_TO_WALLET;
    }

    public function getQrData(): array
    {
        return [
            'holder_id' => $this->walletHolder->id,
            'holder_type' => get_class($this->walletHolder),
            'wallet_type' => $this->walletType->value,
        ];
    }

    public function getPaymentTo(): BaseAuthenticatableWalletHolder|BaseModelWalletHolder
    {
        return $this->walletHolder;
    }

    public function getWalletType(): WalletType
    {
        return $this->walletType;
    }

    public static function fromData(array $data): static|null
    {
        if (
            !isset($data['holder_type']) ||
            !isset($data['holder_id']) ||
            !isset($data['wallet_type'])
        ) {
            return null;
        }

        if (!class_exists($data['holder_type'])) {
            return null;
        }

        $walletType = WalletType::tryFrom($data['wallet_type']);

        if (is_null($walletType)) {
            return null;
        }

        $transaction = null;

        try {
            $walletHolder = $data['holder_type']::find($data['holder_id']);

            if (is_null($walletHolder)) {
                return null;
            }

            $transaction = new static($walletHolder, $walletType);

            return $transaction;
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getWalletTransactionMetaType(): WalletTransactionMetaType
    {
        return WalletTransactionMetaType::WALLET_TO_WALLET_TRANSACTION;
    }

    public function getTransactionReason(): ?Model
    {
        return null;
    }
}
