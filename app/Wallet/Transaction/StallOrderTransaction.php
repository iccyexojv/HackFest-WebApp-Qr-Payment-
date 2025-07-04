<?php

namespace App\Wallet\Transaction;

use App\Enums\StallOrder\StallOrderTransactionStatus;
use App\Models\StallOrder;
use App\Models\StallOrderTransaction as StallOrderTransactionModels;
use App\Wallet\BaseAuthenticatableWalletHolder;
use App\Wallet\BaseModelWalletHolder;
use App\Wallet\Enums\WalletTransactionMetaType;
use App\Wallet\Enums\WalletTransactionType;
use App\Wallet\Enums\WalletType;
use Bavix\Wallet\Models\Transaction;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Database\Eloquent\Model;

class StallOrderTransaction extends BaseTransaction
{

    public function __construct(
        protected StallOrder $stallOrder,
    ) {}

    public function getStallOrder(): StallOrder
    {
        return $this->stallOrder;
    }

    // abstract methods implementation

    public function getWalletTransactionType(): WalletTransactionType
    {
        return WalletTransactionType::STALL_ORDER;
    }

    public function getQrData(): array
    {
        return [
            'code' => $this->stallOrder->code,
        ];
    }

    public function getPaymentTo(): BaseAuthenticatableWalletHolder|BaseModelWalletHolder
    {
        $this->stallOrder->loadMissing('stall');
        return $this->stallOrder->stall;
    }

    public function getWalletType(): WalletType
    {
        return $this->stallOrder->wallet_type;
    }

    public static function fromData(array $data): static|null
    {
        if (
            !isset($data['code']) ||
            !is_string($data['code'])
        ) {
            return null;
        }
        $stallOrder = StallOrder::where('code', $data['code'])->first();

        if (is_null($stallOrder)) {
            return null;
        }

        return new static($stallOrder);
    }

    public function getWalletTransactionMetaType(): WalletTransactionMetaType
    {
        return WalletTransactionMetaType::STALL_ORDER_TRANSACTION;
    }

    public function getTransactionReason(): ?Model
    {
        return $this->getStallOrder();
    }

    protected function afterPay(Wallet $fromWallet)
    {
        $stallOrder = $this->getStallOrder();

        StallOrderTransactionModels::create([
            'stall_id' => $stallOrder->stall_id,
            'stall_order_id' => $stallOrder->id,
            'stall_owner_id' => $stallOrder->stall_owner_id,
            'wallet_type' => $stallOrder->wallet_type,
            'amount' => $stallOrder->getTotalAmount(),
            'status' => StallOrderTransactionStatus::SUCCESS,
            'payer_id' => $fromWallet->holder_id,
            'payer_type' => $fromWallet->holder_type,
        ]);
    }
}
