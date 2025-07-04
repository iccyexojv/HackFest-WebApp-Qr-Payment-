<?php

namespace App\Enums\StallOrder;

use App\Traits\Enums\LabelForDashedValueEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StallOrderStatus: string implements HasLabel, HasColor
{
    use LabelForDashedValueEnum;

    case PENDING_PAYMENT = 'pending-payment';
    case PARTIALLY_PAID = 'partially-paid';
    case PAID = 'paid';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::PENDING_PAYMENT => 'warning',
            self::PARTIALLY_PAID => 'warning',
            self::PAID => 'success',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
        };
    }
}
