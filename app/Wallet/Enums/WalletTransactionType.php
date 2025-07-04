<?php

namespace App\Wallet\Enums;

use App\Traits\Enums\LabelForDashedValueEnum;
use App\Wallet\Transaction\BaseTransaction;
use App\Wallet\Transaction\StallOrderTransaction;
use App\Wallet\Transaction\WalletToWalletTransaction;
use Filament\Support\Contracts\HasLabel;

enum WalletTransactionType: string implements HasLabel
{
    use LabelForDashedValueEnum;

    case WALLET_TO_WALLET = 'wallet-to-wallet';
    case STALL_ORDER = 'stall-order';
}
