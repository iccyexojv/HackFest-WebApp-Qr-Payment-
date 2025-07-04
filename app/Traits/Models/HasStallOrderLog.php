<?php

namespace App\Traits\Models;

use App\Enums\StallOrder\StallOrderLogType;
use App\Models\StallOrderLog;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

trait HasStallOrderLog
{
    // Relationships
    public function stallOrderLogs()
    {
        return $this->morphMany(StallOrderLog::class, 'logged_for');
    }

    // Booting
    protected static function bootHasStallOrderLog(): void
    {
        static::created(function (Model $model) {
            $user = Filament::auth()->user();
            $model->stallOrderLogs()->create([
                'type' => StallOrderLogType::CREATED,
                'old_data' => null,
                'new_data' => collect($model)->toArray(),
                'caused_by_id' => $user?->id,
                'caused_by_type' => $user ? get_class($user) : null,
            ]);
        });

        static::updated(function (Model $model) {
            $user = Filament::auth()->user();
            $model->stallOrderLogs()->create([
                'type' => StallOrderLogType::UPDATED,
                'old_data' => $model->getOriginal(),
                'new_data' => $model->toArray(),
                'caused_by_id' => $user?->id,
                'caused_by_type' => $user ? get_class($user) : null,
            ]);
        });

        static::deleted(function (Model $model) {
            $user = Filament::auth()->user();
            $model->stallOrderLogs()->create([
                'type' => StallOrderLogType::DELETED,
                'old_data' => $model->toArray(),
                'new_data' => null,
                'caused_by_id' => $user?->id,
                'caused_by_type' => $user ? get_class($user) : null,
            ]);
        });
    }
}
