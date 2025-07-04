<?php

namespace App\Wallet\Transaction;

use App\Wallet\BaseAuthenticatableWalletHolder;
use App\Wallet\BaseModelWalletHolder;
use App\Wallet\Enums\WalletTransactionMetaType;
use App\Wallet\Enums\WalletTransactionType;
use App\Wallet\Enums\WalletType;
use Bavix\Wallet\Models\Transaction;
use Bavix\Wallet\Models\Wallet;
use chillerlan\QRCode\QRCode;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\HtmlString;

abstract class BaseTransaction
{

    abstract public function getWalletTransactionType(): WalletTransactionType;

    abstract public function getQrData(): array;

    abstract public function getPaymentTo(): BaseAuthenticatableWalletHolder|BaseModelWalletHolder;

    abstract public function getWalletType(): WalletType;

    abstract public function getTransactionReason(): ?Model;

    abstract public static function fromData(array $data): static|null;

    abstract public function getWalletTransactionMetaType(): WalletTransactionMetaType;

    public function getPaymentToWallet(): Wallet
    {
        $wallet = $this->getWalletType()->of($this->getPaymentTo());

        if (is_null($wallet)) {
            throw new Exception("Receiving wallet cannot be resolved");
        }
        return $wallet;
    }


    public function payFrom(Wallet $fromWallet, float $amount): Transaction|null
    {
        $toWallet = $this->getPaymentToWallet();

        $fromWallet->refresh();

        if ($fromWallet->balance < $amount) {
            return null;
        }

        $transfer = $fromWallet->transfer(
            $toWallet,
            $amount,
            $this->getWalletTransactionMetaType()->getUserMeta($amount)
        );

        $transfer->loadMissing('withdraw');

        if(method_exists($this, 'afterPay')){
            $this->afterPay($fromWallet);
        }


        return $transfer->withdraw;
    }
}
