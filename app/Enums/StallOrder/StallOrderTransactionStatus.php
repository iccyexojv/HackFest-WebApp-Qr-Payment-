<?php

namespace App\Enums\StallOrder;

use App\Traits\Enums\LabelForDashedValueEnum;
use Filament\Support\Contracts\HasLabel;

enum StallOrderTransactionStatus: string implements HasLabel
{
    use LabelForDashedValueEnum;

    case PENDING_PAYER_CONFIRMATION = 'pending-payer-confirmation';
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case STALL_REFUNDED = 'stall-refunded';
    case ADMIN_REFUNDED = 'admin-refunded';
}
