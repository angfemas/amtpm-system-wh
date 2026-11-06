<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HomepageSliderRotateItem extends Model
{
    protected $fillable = [
        'title',
        'description',
        'subtitle',
        'icon_path',
        'position',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'position' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    public function getIconUrlAttribute(): string
    {
        if (!$this->icon_path) {
            return '';
        }

        return Storage::disk('public')->url($this->icon_path);
    }
}
