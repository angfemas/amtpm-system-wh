<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HomepageCarouselItem extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_path',
        'order',
        'caption_position',
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

    public function getImageUrlAttribute(): string
    {
        if (!$this->image_path) {
            return '';
        }

        return Storage::disk('public')->url($this->image_path);
    }
}
