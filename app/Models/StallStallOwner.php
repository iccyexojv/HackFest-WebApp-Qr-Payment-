<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class StallStallOwner extends Pivot
{
    protected $fillable = [
        'stall_id',
        'stall_owner_id',
        'can_manage_stall_profile',
        'can_manage_stall_owner',
    ];

    public function casts()
    {
        return [
            'can_manage_stall_profile' => 'boolean',
            'can_manage_stall_owner' => 'boolean',
        ];
    }

    public $timestamps = true;

    // Relationships

    public function stall()
    {
        return $this->belongsTo(Stall::class);
    }

    public function stallOwner()
    {
        return $this->belongsTo(StallOwner::class);
    }
}
