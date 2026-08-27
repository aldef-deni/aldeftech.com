<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasTranslations;

    /** Fields served per locale; see HasTranslations. */
    protected array $translatable = ['value'];

    protected $fillable = ['key', 'value', 'type', 'group'];

    /**
     * pluck() runs a raw query and never builds a model, so it would bypass the
     * locale-aware accessor entirely. Hydrating the rows costs one query and
     * keeps translations working; the cache is keyed per locale.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            $settings = static::cachedForLocale();

            return $settings[$key] ?? $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    protected static function cachedForLocale(): array
    {
        $locale = app()->getLocale();

        return Cache::rememberForever("site_settings.{$locale}", function () {
            return static::all()
                ->mapWithKeys(fn (self $setting) => [$setting->key => $setting->value])
                ->all();
        });
    }

    public static function set(string $key, mixed $value, string $type = 'text', string $group = 'general'): void
    {
        try {
            static::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => $type, 'group' => $group]
            );

            static::clearCache();
        } catch (\Throwable $e) {
            // Silently ignore if DB unreachable
        }
    }

    public static function getGroup(string $group): array
    {
        try {
            return static::where('group', $group)
                ->get()
                ->mapWithKeys(fn (self $setting) => [$setting->key => $setting->value])
                ->all();
        } catch (\Throwable $e) {
            return [];
        }
    }

    public static function clearCache(): void
    {
        Cache::forget('site_settings');

        foreach (array_keys(config('locales.available', [])) as $locale) {
            Cache::forget("site_settings.{$locale}");
        }
    }
}
