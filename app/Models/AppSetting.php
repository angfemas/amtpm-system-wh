<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    public function scopeByKey($query, $key)
    {
        return $query->where('key', $key);
    }

    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    public static function getValue(string $key, $default = null)
    {
        $setting = static::byKey($key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue(string $key, $value, string $type = 'text', string $group = 'general')
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
            ]
        );
    }
}
