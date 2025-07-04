<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel
{
    case INITIATED= 'initiated';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

public function getLabel(): ?string
    {
        return match ($this) {
            self::INITIATED => 'initiated',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }}