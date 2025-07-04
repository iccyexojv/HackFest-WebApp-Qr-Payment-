<?php

namespace App\Models;

use App\Traits\Models\CausesStallOrderLog;
use App\Wallet\BaseAuthenticatableWalletHolder;
use App\Wallet\Enums\WalletHolderType;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use function Laravel\Prompts\password;

class Participant extends BaseAuthenticatableWalletHolder implements FilamentUser
{
    use CausesStallOrderLog;

     protected $fillable = [
        'name',
        'email',
        'contact_number',
        // 'team_id',
        'password',
        // 'college_name',
        // 'team_name',
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
        return WalletHolderType::PARTICIPANT;
    }

    // Filament panel access
    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'participant' => true,
            default => false,
        };
    }

    // Relationships
    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class);
    }
}

