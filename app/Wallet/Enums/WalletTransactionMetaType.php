<?php

namespace App\Wallet\Enums;

use App\Traits\Enums\LabelForDashedValueEnum;
use Exception;
use Filament\Facades\Filament;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

enum WalletTransactionMetaType: string implements HasLabel
{

    use LabelForDashedValueEnum;

        // Admin operations
    case DEPOSIT = 'deposit';
    case WITHDRAW = 'withdraw';
    case INITIAL_BALANCE = 'initial-balance';
    case CONVERT = 'convert';
    case TRANSFER = 'transfer';

        // Transaction
    case WALLET_TO_WALLET_TRANSACTION = 'wallet-to-wallet-transaction';
    case STALL_ORDER_TRANSACTION = 'stall-order-transaction';

    public function getUserMeta(?float $amount = null, ?Model $paymentReason = null): array
    {
        $user = Filament::auth()->user();
        if (is_null($user)) {
            throw new Exception("User cannot be resolved");
        }
        return [
            'processed_by_id' => $user->id,
            'processed_by_name' => $user->name,
            'processed_for' => $this->value,
            'processed_at' => now()->toString(),
            'amount' => $amount,
            'reason_id' => $paymentReason?->getKey(),
            'reason_type' => is_null($paymentReason) ? null : get_class($paymentReason),
        ];
    }

    public function getSystemMeta(?float $amount = null, ?Model $paymentReason = null): array
    {
        return [
            'processed_by_id' => -1,
            'processed_by_name' => "SYSTEM",
            'processed_for' => $this->value,
            'processed_at' => now()->toString(),
            'amount' => $amount,
            'reason_id' => $paymentReason?->getKey(),
            'reason_type' => is_null($paymentReason) ? null : get_class($paymentReason),
        ];
    }
}
