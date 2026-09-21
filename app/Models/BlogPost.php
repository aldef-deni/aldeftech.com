<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    /**
     * The UUID a generator once appended to slugs:
     * "judul-artikel-d39a35fb-1266-46b9-be30-3e25d5404493".
     *
     * It addressed nothing — not the article's identity, not a keyword — and
     * every one of those URLs had to be read by a human before it could be
     * shared. Clean slugs are the rule now; this pattern exists so the old
     * addresses can be recognised and forwarded instead of breaking.
     */
    public const UUID_SUFFIX = '/-[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';

    /** Fields served per locale; see HasTranslations. */
    protected array $translatable = ['title', 'excerpt', 'content'];

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'featured_image',
        'category_id', 'author_id', 'status', 'published_at',
        'meta_title', 'meta_description', 'canonical_url',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        // Every save goes through the same rule, so a slug typed by hand, sent
        // by the AI generator or written by a seeder ends up identical: a clean
        // slug, unique across the table. A post whose slug is untouched keeps it.
        static::saving(function (BlogPost $model) {
            if (empty($model->slug) || $model->isDirty('slug')) {
                $model->slug = static::uniqueSlug(
                    static::cleanSlug((string) ($model->slug ?: $model->title)),
                    $model->exists ? $model->getKey() : null
                );
            }
        });

        // Renaming an article must not break the link Google already has.
        static::updated(function (BlogPost $model) {
            if ($model->wasChanged('slug')) {
                BlogPostSlugRedirect::record($model->getKey(), (string) $model->getOriginal('slug'));
            }
        });
    }

    /**
     * A slug a person can read: no UUID tail, no stray separators.
     */
    public static function cleanSlug(string $value): string
    {
        $slug = preg_replace(self::UUID_SUFFIX, '', Str::slug($value)) ?? Str::slug($value);
        $slug = trim($slug, '-');

        return $slug !== '' ? $slug : Str::slug($value);
    }

    /**
     * The requested slug, or the same slug with -2, -3 … appended when another
     * article already holds it. Soft-deleted rows count: their URLs were public
     * once and must not be handed to a different article.
     */
    public static function uniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $base = $slug !== '' ? $slug : 'artikel';
        $candidate = $base;
        $suffix = 1;

        while (static::withTrashed()
            ->where('slug', $candidate)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $suffix++;
            $candidate = $base . '-' . $suffix;
        }

        return $candidate;
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tag');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at && $this->published_at->isPast();
    }
}
