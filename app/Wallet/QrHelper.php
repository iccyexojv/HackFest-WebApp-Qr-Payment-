<?php

namespace App\Wallet;

use App\Models\StallOrder;
use App\Wallet\Enums\WalletTransactionType;
use App\Wallet\Transaction\BaseTransaction;
use App\Wallet\Transaction\StallOrderTransaction;
use App\Wallet\Transaction\WalletToWalletTransaction;
use chillerlan\QRCode\QRCode;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Throwable;

class QrHelper
{

    public static function generate(
        BaseTransaction $transaction,
    ): string {
        $rawData = [
            'type' => $transaction->getWalletTransactionType(),
            'data' => $transaction->getQrData(),
        ];

        $encryptedData = Crypt::encryptString(json_encode($rawData));
        $qrCode = (new QRCode)->render($encryptedData);
        return $qrCode;
    }

    public static function parse(string $rawData): BaseTransaction|null
    {
        try {

            $qrData = json_decode(Crypt::decryptString($rawData), true);

            if (
                !isset($qrData['type']) ||
                !is_string($qrData['type']) ||
                !isset($qrData['data']) ||
                !is_array($qrData['data'])
            ) {
                return null;
            }

            return match ($qrData['type']) {
                WalletTransactionType::WALLET_TO_WALLET->value => WalletToWalletTransaction::fromData($qrData['data']),
                WalletTransactionType::STALL_ORDER->value => StallOrderTransaction::fromData($qrData['data']),
                default => null,
            };
        } catch (Throwable $throwable) {
            return null;
        }
    }
}
