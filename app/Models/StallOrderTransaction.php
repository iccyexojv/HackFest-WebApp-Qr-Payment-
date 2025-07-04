<?php

namespace App\Models;

use App\Enums\StallOrder\StallOrderTransactionStatus;
use App\Traits\Models\HasStallOrderLog;
use Illuminate\Database\Eloquent\Model;

class StallOrderTransaction extends Model
{
    use HasStallOrderLog;

    protected $fillable = [
        'stall_id',
        'stall_order_id',
        'stall_owner_id',
        'wallet_type',
        'amount',
        'status',
        'payer_id',
        'payer_type',
    ];

    protected function casts()
    {
        return [
            'status' => StallOrderTransactionStatus::class,
        ];
    }

    // Relationships

    public function stall()
    {
        return $this->belongsTo(Stall::class);
    }

    public function stallOrder()
    {
        return $this->belongsTo(StallOrder::class);
    }

    public function stallOwner()
    {
        return $this->belongsTo(StallOwner::class);
    }

    public function payer()
    {
        return $this->morphTo();
    }
}
