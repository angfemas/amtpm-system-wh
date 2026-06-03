<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceSchedule extends Model
{
    protected $fillable = [
        'unit_id',
        'next_due_date',
        'last_reminder_stage',
        'last_notified_at',
    ];

    protected function casts(): array
    {
        return [
            'next_due_date' => 'date',
            'last_notified_at' => 'datetime',
        ];
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
