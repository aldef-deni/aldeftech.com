<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * A client whose logo is shown as proof on the homepage and on the About page.
 *
 * Which page a logo lands on is data, not code: show_home and show_about are
 * independent, so the same row can serve either page, both, or neither while
 * it is being prepared.
 */
class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'logo', 'website_url', 'is_published',
        'show_home', 'show_about', 'is_featured', 'sort_order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'show_home' => 'boolean',
        'show_about' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Live on the site. Named "active" because that is what the dashboard calls
     * it and what the public scopes read as; the column stays is_published to
     * match the rest of the content tables.
     */
    public function scopeActive($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Exactly what the homepage marquee may render.
     *
     * The placement scopes carry the whole rule — live, placed here, and
     * carrying a logo — so a controller cannot half-apply it and publish
     * something the dashboard never intended to show.
     */
    public function scopeForHome($query)
    {
        return $query->active()->withLogo()->where('show_home', true);
    }

    /** Exactly what the About grid may render. */
    public function scopeForAbout($query)
    {
        return $query->active()->withLogo()->where('show_about', true);
    }

    /** A row with no logo would render an empty tile, so it never reaches a page. */
    public function scopeWithLogo($query)
    {
        return $query->whereNotNull('logo')->where('logo', '<>', '');
    }

    /** Featured first, then the editor's order, then the newest addition. */
    public function scopeDisplayOrder($query)
    {
        return $query->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('created_at');
    }

    public function isActive(): bool
    {
        return (bool) $this->is_published;
    }

    /**
     * The link target, or null when there is nothing safe to link to.
     *
     * Only http(s) reaches an href: a half-typed or hostile value is stored as
     * text and simply not made clickable, rather than emitting a dead anchor.
     */
    public function website(): ?string
    {
        $url = trim((string) $this->website_url);

        return preg_match('#^https?://#i', $url) ? $url : null;
    }

    /** Host shown in the dashboard table, without the scheme or a trailing slash. */
    public function websiteHost(): ?string
    {
        $url = $this->website();

        if (! $url) {
            return null;
        }

        $host = preg_replace('#^https?://(www\.)?#i', '', $url);

        return rtrim($host, '/') ?: null;
    }
}
