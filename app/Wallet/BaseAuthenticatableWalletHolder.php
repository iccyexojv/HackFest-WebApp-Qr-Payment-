<?php

namespace App\Wallet;

use App\Wallet\Contracts\WalletHolderContract;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Traits\HasWallets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Crypt;

abstract class BaseAuthenticatableWalletHolder extends Authenticatable implements WalletHolderContract
{
    use HasWallet;
    use HasWallets;

    public static function booted()
    {
        self::created(function (BaseAuthenticatableWalletHolder $walletHolder) {
            $walletHolder->setupWallets();
        });
    }

    public function setupWallets()
    {
        $this->getWalletHolderType()
            ->setupWallets($this);
    }

    public function getReceiveQrData(): string
    {
        return Crypt::encryptString(
            json_encode([
                'holder_id' => $this->id,
                'holder_type' => get_class($this),
            ])
        );
    }

    public static function parseReceiveQrData(string $data): ?Model
    {
        $data = json_decode(Crypt::decryptString($data), true);

        if(!isset($data['holder_type']) || !isset($data['holder_id'])) {
            return null;
        }

        if(!class_exists($data['holder_type'])) {
            return null;
        }

        return $data['holder_type']::find($data['holder_id']);
    }
}
