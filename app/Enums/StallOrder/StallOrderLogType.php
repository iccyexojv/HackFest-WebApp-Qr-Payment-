<?php

namespace App\Enums\StallOrder;

use App\Traits\Enums\LabelForDashedValueEnum;
use Filament\Support\Contracts\HasLabel;

enum StallOrderLogType: string implements HasLabel
{
    use LabelForDashedValueEnum;

    case CREATED = 'created';
    case UPDATED = 'updated';
    case DELETED = 'deleted';
}
