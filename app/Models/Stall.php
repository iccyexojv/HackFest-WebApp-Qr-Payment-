<?php

namespace App\Models;

use App\Models\StallOwner;
use App\Enums\StallType;
use App\Wallet\BaseModelWalletHolder;
use App\Wallet\Enums\WalletHolderType;
use Illuminate\Database\Eloquent\Model;

class Stall extends BaseModelWalletHolder
{
    protected $fillable = [
        'name',
        // 'stall_owner_id',
        'type',
        'location',
    ];

    protected function casts(): array
    {
        return [
            'type' => StallType::class,
        ];
    }

    // Wallet
    public function getWalletHolderType(): WalletHolderType
    {
        return WalletHolderType::STALL;
    }

    // Relationships

    public function stallOwners()
    {
        return $this->belongsToMany(StallOwner::class)
            ->using(StallStallOwner::class)
            ->withPivot([
                'can_manage_stall_profile',
                'can_manage_stall_owner',
            ])
            ->withTimestamps();
    }

// public function orders()
// {
//     return $this->hasMany(Order::class);
// }


    public function stallItems()
    {
        return $this->hasMany(StallItem::class);
    }

    public function stallOrders()
    {
        return $this->hasMany(StallOrder::class);
    }
}
