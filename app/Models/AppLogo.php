<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AppLogo extends Model
{
    protected $fillable = [
        'logo_type',
        'image_path',
        'alt_text',
        'width',
        'height',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'width' => 'integer',
        'height' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('logo_type', $type);
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image_path) {
            return '';
        }

        return Storage::disk('public')->url($this->image_path);
    }

    /**
     * Get active logo by type
     */
    public static function getActiveLogo(string $type = 'default')
    {
        return static::active()->byType($type)->first();
    }
}
