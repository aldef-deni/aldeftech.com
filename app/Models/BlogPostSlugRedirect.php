<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BlogPostSlugRedirect extends Model
{
    /**
     * The table the migration actually creates. Naming it explicitly matters:
     * Eloquent would otherwise look for blog_post_slug_redirects and every
     * lookup would fail quietly, leaving renamed articles to 404.
     */
    protected $table = 'article_slug_redirects';

    public $timestamps = true;

    protected $fillable = [
        'article_id',
        'old_slug',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class, 'article_id');
    }

    /**
     * Store an old slug so inbound links keep working via a 301.
     */
    public static function record(int $articleId, string $oldSlug): void
    {
        $normalized = Str::slug($oldSlug);

        if ($normalized === '' || $normalized === Str::slug(BlogPost::find($articleId)?->slug)) {
            return;
        }

        // firstOrCreate, not a check-then-insert: two saves in the same second
        // must not trip the (article_id, old_slug) unique index.
        //
        // Guarded: this table is additive, and an article must still be saveable
        // during the window before its migration has run.
        try {
            static::firstOrCreate([
                'article_id' => $articleId,
                'old_slug' => $normalized,
            ]);
        } catch (\Throwable $e) {
            // The slug is already saved, so a failure here costs a redirect and
            // must not fail the save — but it must not pass unnoticed either.
            report($e);
        }
    }

    /**
     * Find a current post for an old slug.
     */
    public static function findCurrentPost(string $oldSlug): ?BlogPost
    {
        // A URL that has never been renamed must still answer normally before
        // the table exists, so a missing table reads as "no redirect", not 500.
        try {
            $redirect = static::where('old_slug', $oldSlug)->first();
        } catch (\Throwable $e) {
            return null;
        }

        if (! $redirect) {
            return null;
        }

        return $redirect->article;
    }
}
