<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ChecklistItem extends Model
{
    use LogsActivity;

    protected $fillable = [
        'nama_item',
        'deskripsi',
        'tipe',
        'options',
        'urutan',
        'is_required',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Many-to-many: checklist item <-> unit categories
    public function unitCategories()
    {
        return $this->belongsToMany(UnitCategory::class, 'checklist_item_unit_category');
    }

    // One-to-many: checklist item -> sub items
    public function subItems()
    {
        return $this->hasMany(ChecklistSubItem::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan');
    }
}
