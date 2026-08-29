<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Per-page, per-language meta that an editor can change without a deploy.
 *
 * Nothing here is required: a blank row falls through to whatever the page
 * already sets for itself, and then to the site-wide defaults. That ordering
 * means turning this feature on can never leave a page with an empty title.
 */
class PageSeo extends Model
{
    protected $table = 'page_seo';

    protected $fillable = [
        'route_name', 'locale', 'meta_title', 'meta_description', 'og_image', 'noindex',
    ];

    protected $casts = [
        'noindex' => 'boolean',
    ];

    /**
     * Public pages an editor may tune, in menu order.
     *
     * Detail pages are absent on purpose — a portfolio item or blog post
     * carries its own meta fields on its own edit screen.
     *
     * @return array<string, string>
     */
    public static function managedPages(): array
    {
        return [
            'home' => 'Beranda',
            'services' => 'Layanan',
            'solutions' => 'Solusi',
            'portfolio' => 'Portofolio',
            'about' => 'Tentang',
            'blog' => 'Insight',
            'faq' => 'FAQ',
            'contact' => 'Kontak',
        ];
    }

    /**
     * The override for the page being rendered, or null when there is none.
     *
     * Cached as one map per locale: the layout asks for this on every request,
     * and a per-page query would add a round trip to every page load.
     */
    public static function forCurrentRoute(): ?self
    {
        $name = locale_route_name(\Illuminate\Support\Facades\Route::currentRouteName());

        if (! $name || ! array_key_exists($name, static::managedPages())) {
            return null;
        }

        return static::cachedForLocale()[$name] ?? null;
    }

    /** @return array<string, self> */
    protected static function cachedForLocale(): array
    {
        $locale = app()->getLocale();

        return Cache::rememberForever("page_seo.{$locale}", function () use ($locale) {
            return static::query()->where('locale', $locale)->get()
                ->keyBy('route_name')->all();
        });
    }

    public static function clearCache(): void
    {
        foreach (array_keys(config('locales.available', [])) as $code) {
            Cache::forget("page_seo.{$code}");
        }
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::clearCache());
        static::deleted(fn () => static::clearCache());
    }
}
