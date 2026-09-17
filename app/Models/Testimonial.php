<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    /** Fields served per locale; see HasTranslations. */
    protected array $translatable = ['position', 'testimonial'];

    protected $fillable = [
        'client_name', 'company', 'position', 'photo',
        'testimonial', 'rating', 'is_featured', 'is_published',
        'published_at', 'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Visible on the public site: published and not dated in the future.
     *
     * published_at is written whenever an editor publishes, so a null date here
     * means the row was never actually published and must stay hidden.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /** Editor order: what an editor arranged first, newest publication second. */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('published_at');
    }

    /** Featured first, then the editor's order, then the newest. */
    public function scopeDisplayOrder($query)
    {
        return $query->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('published_at');
    }

    /**
     * Only rows carrying copy in this locale.
     *
     * The base columns hold Indonesian, so the Indonesian page shows everything.
     * The English page must not fall back to Indonesian quotes: HasTranslations
     * falls back for rendering, which is right for page chrome but wrong for a
     * quote attributed to a person, so untranslated rows are filtered out here.
     */
    public function scopeTranslatedIn(Builder $query, string $locale): Builder
    {
        if ($locale === config('locales.default', 'id')) {
            return $query;
        }

        return $query->whereNotNull("translations->{$locale}->testimonial");
    }

    public function isScheduled(): bool
    {
        return $this->is_published && $this->published_at && $this->published_at->isFuture();
    }

    public function isVisible(): bool
    {
        return $this->is_published && $this->published_at && $this->published_at->isPast();
    }

    /** Detail shown under the name: "Jabatan · Perusahaan", either part optional. */
    public function roleLine(): string
    {
        return trim(($this->position ? $this->position . ' · ' : '') . $this->company, ' ·');
    }

    /**
     * Locales this testimonial can actually be read in: Indonesian always,
     * English only once an editor has written the quote in English.
     */
    public function localeBadges(): array
    {
        $badges = [strtoupper(config('locales.default', 'id'))];

        foreach (array_keys(config('locales.available', [])) as $code) {
            if ($code !== config('locales.default', 'id') && $this->translate('testimonial', $code)) {
                $badges[] = strtoupper($code);
            }
        }

        return $badges;
    }
}
