<?php

namespace App\Enums;

use App\Traits\Enums\LabelForDashedValueEnum;
use Filament\Support\Contracts\HasLabel;

enum StallType: string implements HasLabel
{
    use LabelForDashedValueEnum;

    case FOOD = 'food';
    case GAME = 'game';
    case OTHERS = 'others';

}
