<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    protected $fillable = ['section_key', 'title', 'subtitle', 'content', 'is_visible', 'sort_order'];

    protected $casts = [
        'content' => 'array',
        'is_visible' => 'boolean',
    ];

    public static function getByKey(string $key): ?static
    {
        return static::where('section_key', $key)->first();
    }

    public static function getSectionContent(string $key, $default = null)
    {
        $section = static::getByKey($key);
        return $section?->content[$key] ?? $default;
    }
}
