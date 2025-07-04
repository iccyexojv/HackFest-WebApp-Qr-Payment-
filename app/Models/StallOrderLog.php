<?php

namespace App\Models;

use App\Enums\StallOrder\StallOrderLogType;
use Illuminate\Database\Eloquent\Model;

class StallOrderLog extends Model
{
    protected $fillable = [
        'type',
        'old_data',
        'new_data',
        'caused_by_id',
        'caused_by_type',
        'logged_for_id',
        'logged_for_type',
    ];

    protected function casts(): array
    {
        return [
            'type' => StallOrderLogType::class,
            'old_data' => 'array',
            'new_data' => 'array',
        ];
    }

    // Relationships

    public function causedBy()
    {
        return $this->morphTo('caused_by');
    }

    public function loggedFor()
    {
        return $this->morphTo('logged_for');
    }
}
