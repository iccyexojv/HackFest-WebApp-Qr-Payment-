<?php

namespace App\Filament\Widgets;

use App\Wallet\Enums\WalletType;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WalletsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $owner = Filament::auth()->user();
        if(Filament::getCurrentPanel()->getId() === 'stall'){
            $owner = Filament::getTenant();
        }
        $fpWallet = $owner->getWallet(WalletType::FEST_POINT->value);
        $gpWallet = $owner->getWallet(WalletType::GAME_POINT->value);
        $kpWallet = $owner->getWallet(WalletType::KBC_POINT->value);
        return [
            Stat::make('Fest Points', $fpWallet->balance),
            Stat::make('Game Points', $gpWallet->balance),
            Stat::make('KBC Points', $kpWallet->balance),
        ];
    }
}
