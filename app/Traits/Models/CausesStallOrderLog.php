<?php

namespace App\Traits\Models;

use App\Enums\StallOrder\StallOrderLogType;
use App\Models\StallOrderLog;
use Illuminate\Database\Eloquent\Model;

trait CausesStallOrderLog
{
    // Relationships
    public function stallOrderLogs()
    {
        return $this->morphMany(StallOrderLog::class, 'caused_by');
    }
}
