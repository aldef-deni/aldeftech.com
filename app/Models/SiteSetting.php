<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group'];

    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            $settings = Cache::rememberForever('site_settings', function () {
                return static::pluck('value', 'key')->toArray();
            });

            return $settings[$key] ?? $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    public static function set(string $key, mixed $value, string $type = 'text', string $group = 'general'): void
    {
        try {
            static::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => $type, 'group' => $group]
            );

            Cache::forget('site_settings');
        } catch (\Throwable $e) {
            // Silently ignore if DB unreachable
        }
    }

    public static function getGroup(string $group): array
    {
        try {
            return static::where('group', $group)->pluck('value', 'key')->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    public static function clearCache(): void
    {
        Cache::forget('site_settings');
    }
}
