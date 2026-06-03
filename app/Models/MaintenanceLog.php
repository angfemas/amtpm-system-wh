<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MaintenanceLog extends Model
{
    use LogsActivity;

    protected $fillable = [
        'unit_id',
        'operator_id',
        'leader_id',
        'checklist_data',
        'custom_field_data',
        'kilometer_input',
        'hour_meter_input',
        'foto_paths',
        'catatan_kerusakan',
        'status',
        'tag_type',
        'tag_description',
        'submitted_at',
        'approved_at',
        'completed_at',
    ];

    protected $casts = [
        'checklist_data' => 'array',
        'custom_field_data' => 'array',
        'kilometer_input' => 'decimal:2',
        'hour_meter_input' => 'decimal:2',
        'foto_paths' => 'array',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function redWhiteTags(): HasMany
    {
        return $this->hasMany(RedWhiteTag::class, 'maintenance_log_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('submitted_at', [$startDate, $endDate]);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->status === 'submitted';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}
