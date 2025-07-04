<?php

namespace App\Models;

use App\Traits\Models\CausesStallOrderLog;
use App\Wallet\BaseAuthenticatableWalletHolder;
use App\Wallet\Enums\WalletHolderType;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class Visitor extends BaseAuthenticatableWalletHolder implements FilamentUser
{

    use CausesStallOrderLog;

    protected $fillable = [
        'name',
        'email',
        'contact_number',
        'address',
        'password',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Wallet

    public function getWalletHolderType(): WalletHolderType
    {
        return WalletHolderType::VISITOR;
    }

    // Filament panel access
    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'visitor' => true,
            default => false,
        };
    }
}
