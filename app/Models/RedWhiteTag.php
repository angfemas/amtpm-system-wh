<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RedWhiteTag extends Model
{
    use LogsActivity;

    protected $fillable = [
        'unit_id',
        'maintenance_log_id',
        'created_by',
        'photo_path',
        'tag_type',
        'description',
        'severity',
        'status',
        'target_resolution_date',
        'actual_resolution_date',
        'resolution_notes',
        'resolved_by',
    ];

    protected $casts = [
        'target_resolution_date' => 'date',
        'actual_resolution_date' => 'date',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function maintenanceLog(): BelongsTo
    {
        return $this->belongsTo(MaintenanceLog::class, 'maintenance_log_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function scopeByType($query, $type)
    {
        return $query->where('tag_type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isResolved(): bool
    {
        return $this->status === 'resolved' || $this->status === 'closed';
    }

    public function isRedTag(): bool
    {
        return $this->tag_type === 'red_tag';
    }

    public function isWhiteTag(): bool
    {
        return $this->tag_type === 'white_tag';
    }
}
