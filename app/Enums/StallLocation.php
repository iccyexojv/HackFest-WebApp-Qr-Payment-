<?php

namespace App\Enums;

use App\Traits\Enums\LabelForDashedValueEnum;
use Filament\Support\Contracts\HasLabel;


enum StallLocation:string implements HasLabel
{
    use LabelForDashedValueEnum;

    case BASKETBALL_COURT = 'basketball-court';
    case MAIN_ENTRANCE = 'main-entrance';

    case PARKING_AREA = 'parking-area';

    case GAME_AREA = 'game-area';

}
