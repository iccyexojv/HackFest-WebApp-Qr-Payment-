<?php

namespace App\Models;

use App\Enums\StallOrder\StallOrderItemStatus;
use App\Traits\Models\HasStallOrderLog;
use Illuminate\Database\Eloquent\Model;

class StallOrderItem extends Model
{
    use HasStallOrderLog;

    protected $fillable = [
        'stall_id',
        'stall_order_id',
        'stall_item_id',
        'fest_point_price',
        'game_point_price',
        'quantity',
        'fest_point_total_amount',
        'game_point_total_amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'fest_point_price' => 'decimal:2',
            'game_point_price' => 'decimal:2',
            'quantity' => 'decimal:2',
            'fest_point_total_amount' => 'decimal:2',
            'game_point_total_amount' => 'decimal:2',
            'status' => StallOrderItemStatus::class,
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

    public function stallItem()
    {
        return $this->belongsTo(StallItem::class);
    }
}
