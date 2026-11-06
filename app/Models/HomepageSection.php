<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HomepageSection extends Model
{
    protected $fillable = [
        'key',
        'title',
        'content',
        'image_path',
        'background_color',
        'text_color',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeByKey($query, string $key)
    {
        return $query->where('key', $key);
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image_path) {
            return '';
        }

        return Storage::disk('public')->url($this->image_path);
    }
}
