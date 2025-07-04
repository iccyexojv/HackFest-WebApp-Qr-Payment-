<?php

namespace App\Models;

use App\Traits\Models\CausesStallOrderLog;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;

class StallOwner extends Authenticatable implements FilamentUser, HasTenants
{
    use CausesStallOrderLog;

    protected $fillable = [
        'name',
        'email',
        'password',
        'contact_number',
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

    // Filament multi tenancy
    public function getTenants(Panel $panel): Collection
    {
        return $this->stalls;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->stalls()->whereKey($tenant)->exists();
    }

    // Filament panel access
    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'stall' => true,
            default => false,
        };
    }


    // Relationships

    public function stalls()
    {
        return $this->belongsToMany(Stall::class)
            ->using(StallStallOwner::class)
            ->withPivot([
                'can_manage_stall_profile',
                'can_manage_stall_owner',
            ])
            ->withTimestamps();
    }
}
