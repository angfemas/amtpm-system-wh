<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Unit extends Model
{
    use LogsActivity;

    protected $table = 'units';

    protected $fillable = [
        'nomor_urut',
        'kode_unit',
        'nama_unit',
        'unit_category_id',
        'warehouse_area_id',
        'jenis_maintenance',
        'tanggal_maintenance_terakhir',
        'interval_hari',
        'kilometer',
        'hour_meter',
        'status',
        'qr_code',
        'foto_unit',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'nomor_urut' => 'integer',
        'tanggal_maintenance_terakhir' => 'date',
        'kilometer' => 'decimal:2',
        'hour_meter' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'nomor_display',
        'maintenance_due_date',
        'is_overdue',
        'status_badge',
        'jenis_maintenance_display',
        'foto_url',
        'foto_alt',
    ];

    // Relationships
    public function unitCategory(): BelongsTo
    {
        return $this->belongsTo(UnitCategory::class, 'unit_category_id');
    }

    public function warehouseArea(): BelongsTo
    {
        return $this->belongsTo(WarehouseArea::class, 'warehouse_area_id');
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class, 'unit_id');
    }

    public function redWhiteTags(): HasMany
    {
        return $this->hasMany(RedWhiteTag::class, 'unit_id');
    }

    public function customFieldValues(): HasMany
    {
        return $this->hasMany(CustomFieldValue::class, 'unit_id');
    }

    public function maintenanceSchedule(): HasOne
    {
        return $this->hasOne(MaintenanceSchedule::class, 'unit_id');
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('unit_category_id', $categoryId);
    }

    public function scopeByArea(Builder $query, int $areaId): Builder
    {
        return $query->where('warehouse_area_id', $areaId);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $q) use ($search) {
            $q->where('kode_unit', 'like', "%{$search}%")
              ->orWhere('nama_unit', 'like', "%{$search}%")
              ->orWhere('nomor_urut', 'like', "%{$search}%")
              ->orWhere('keterangan', 'like', "%{$search}%");
        });
    }

    // Accessors
    public function getMaintenanceDueDateAttribute(): ?\Illuminate\Support\Carbon
    {
        if (!$this->tanggal_maintenance_terakhir) {
            return null;
        }

        return $this->tanggal_maintenance_terakhir->copy()->addDays((int) $this->interval_hari);
    }

    public function getIsOverdueAttribute(): bool
    {
        $dueDate = $this->maintenance_due_date;

        return $dueDate !== null && $dueDate->isPast();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'maintenance' => 'bg-yellow-100 text-yellow-800',
            'overdue' => 'bg-red-100 text-red-800',
            'inactive' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getJenisMaintenanceDisplayAttribute(): string
    {
        return match($this->jenis_maintenance) {
            'preventive' => 'Preventive',
            'corrective' => 'Corrective',
            'predictive' => 'Predictive',
            default => ucfirst($this->jenis_maintenance),
        };
    }

    public function getFotoUrlAttribute(): ?string
    {
        if (! $this->foto_unit) {
            return null;
        }

        $foto = trim($this->foto_unit);

        if (str_starts_with($foto, 'http://') || str_starts_with($foto, 'https://')) {
            return $foto;
        }

        if (str_starts_with($foto, '/storage/')) {
            return $foto;
        }

        if (str_starts_with($foto, 'storage/')) {
            return '/' . $foto;
        }

        if (Storage::disk('public')->exists($foto)) {
            return '/storage/' . ltrim($foto, '/');
        }

        return '/storage/' . ltrim($foto, '/');
    }

    public function getFotoAltAttribute(): string
    {
        return $this->nama_unit ? "Foto unit {$this->nama_unit}" : 'Foto unit';
    }

    public function getKodeUnitFormattedAttribute(): string
    {
        return strtoupper($this->kode_unit);
    }

    /**
     * Identitas penomoran kereta dengan format: no nama_unit unit_category.
     * Contoh: "10 kereta universal MTC setting sport".
     */
    public function getNomorDisplayAttribute(): string
    {
        $parts = [];

        if ($this->nomor_urut !== null) {
            $parts[] = $this->nomor_urut;
        }

        if ($this->nama_unit) {
            $parts[] = $this->nama_unit;
        }

        $categoryName = $this->unitCategory?->name;
        if ($categoryName) {
            $parts[] = $categoryName;
        }

        return trim(implode(' ', $parts));
    }

    /**
     * Identitas ringkas: no nama_unit (tanpa kategori).
     * Dipakai di tempat yang sudah menampilkan kategori/area secara terpisah.
     */
    public function getNomorNamaAttribute(): string
    {
        if ($this->nomor_urut !== null) {
            return trim("{$this->nomor_urut} {$this->nama_unit}");
        }

        return (string) $this->nama_unit;
    }

    /**
     * Nomor urut berikutnya berdasarkan nomor terakhir yang tersimpan.
     */
    public static function nextNomorUrut(): int
    {
        return (int) static::max('nomor_urut') + 1;
    }

    // Activity Logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
