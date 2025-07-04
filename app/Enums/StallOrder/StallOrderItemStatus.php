<?php

namespace App\Enums\StallOrder;

use App\Traits\Enums\LabelForDashedValueEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StallOrderItemStatus:string  implements HasLabel, HasColor
{
    use LabelForDashedValueEnum;

    case PENDING = 'pending';
    case ORDERED = 'ordered';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

        public function getColor(): string | array | null{
            return match ($this) {
                self::PENDING => 'warning',
                self::ORDERED => 'info',
                self::COMPLETED => 'success',
                self::CANCELLED => 'danger',
            };
        }
}
