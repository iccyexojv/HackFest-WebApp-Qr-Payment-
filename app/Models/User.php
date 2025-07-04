<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Models\CausesStallOrderLog;
use App\Wallet\BaseAuthenticatableWalletHolder;
use App\Wallet\Enums\WalletHolderType;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends BaseAuthenticatableWalletHolder implements FilamentUser
{
    use CausesStallOrderLog;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'can_manage_wallet'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'can_manage_wallet' => 'boolean',
        ];
    }

    // Wallet
    public function getWalletHolderType(): WalletHolderType
    {
        return WalletHolderType::ORGANIZER;
    }

    // Filament panel access
    public function canAccessPanel(Panel $panel): bool
    {
        $canAccessPanel = match ($panel->getId()) {
            'wallet' => $this->can_manage_wallet ?? false,
            'admin' => true,
            default => false,
        };

        return $canAccessPanel;
    }
}
