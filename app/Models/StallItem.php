<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StallItem extends Model
{
    protected $fillable = [
        'stall_id',
        'name',
        'description',
        'fest_point_price',
        'game_point_price',
    ];

    public function stall()
    {
        return $this->belongsTo(Stall::class);
    }
}
