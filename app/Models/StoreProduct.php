<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * A shoe in the Aldef Store catalogue. Deliberately has no price: visitors ask
 * for price and stock over WhatsApp.
 */
class StoreProduct extends Model
{
    use SoftDeletes;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    public const SKU_PREFIX = 'ATS';

    protected $fillable = [
        'store_category_id', 'name', 'slug', 'brand', 'description', 'material',
        'size_system', 'size_guide', 'status', 'is_featured', 'sort_order',
        'published_at', 'meta_title', 'meta_description',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Create a product with the next SKU: ATS + DDMMYYYY + 5-digit running
     * number that never resets and never reuses a deleted product's number.
     *
     * The number comes from the highest sku_sequence ever issued, soft-deleted
     * rows included. Two admins saving at the same instant would pick the same
     * number; the unique index rejects the second insert and it is retried.
     */
    public static function createWithSku(array $attributes): self
    {
        $attempts = 0;

        while (true) {
            try {
                return DB::transaction(function () use ($attributes) {
                    $next = (int) static::withTrashed()->lockForUpdate()->max('sku_sequence') + 1;

                    $product = new static($attributes);
                    $product->sku_sequence = $next;
                    $product->sku = static::formatSku($next);
                    $product->save();

                    return $product;
                });
            } catch (QueryException $e) {
                if (++$attempts >= 3 || ! static::isUniqueViolation($e)) {
                    throw $e;
                }
            }
        }
    }

    public static function formatSku(int $sequence, ?\DateTimeInterface $date = null): string
    {
        $date ??= now();

        return self::SKU_PREFIX . $date->format('dmY') . str_pad((string) $sequence, 5, '0', STR_PAD_LEFT);
    }

    private static function isUniqueViolation(QueryException $e): bool
    {
        // MySQL 23000/1062, SQLite 23000 ("UNIQUE constraint failed").
        return ($e->errorInfo[0] ?? null) === '23000';
    }

    public function category()
    {
        return $this->belongsTo(StoreCategory::class, 'store_category_id');
    }

    /** Gallery items, photos and videos, main photo first then in order. */
    public function images()
    {
        return $this->hasMany(StoreProductImage::class)->orderByDesc('is_primary')->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(StoreProductVariant::class)->orderBy('sort_order');
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeOrdered($query)
    {
        return $query->orderByDesc('is_featured')->orderBy('sort_order')->orderByDesc('published_at')->orderByDesc('id');
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /** The photo shown on catalogue cards and in link previews; never a video. */
    public function primaryImage(): ?StoreProductImage
    {
        return $this->images->first(fn ($item) => ! $item->isVideo());
    }

    /** @return Collection<int, string> sizes in the order the admin entered them */
    public function sizes(): Collection
    {
        return $this->variants->pluck('size')->filter(fn ($s) => $s !== '')->unique()->values();
    }

    /** @return Collection<int, string> */
    public function colors(): Collection
    {
        return $this->variants->pluck('color')->filter(fn ($c) => $c !== '')->unique()->values();
    }

    /** @return Collection<int, string> sizes with at least one available colour */
    public function availableSizes(): Collection
    {
        return $this->variants->where('is_available', true)
            ->pluck('size')->filter(fn ($s) => $s !== '')->unique()->values();
    }

    /**
     * null when the admin recorded no variants at all: availability is then
     * simply unknown and the visitor asks over WhatsApp.
     */
    public function isAvailable(): ?bool
    {
        if ($this->variants->isEmpty()) {
            return null;
        }

        return $this->variants->contains('is_available', true);
    }

    /** Short size summary for a catalogue card, e.g. "EU 39–44". */
    public function sizeSummary(): ?string
    {
        $sizes = $this->availableSizes();

        if ($sizes->isEmpty()) {
            return null;
        }

        $numeric = $sizes->every(fn ($s) => is_numeric($s));
        $label = $numeric && $sizes->count() > 2
            ? $sizes->sort(fn ($a, $b) => (float) $a <=> (float) $b)->first() . '–' . $sizes->sort(fn ($a, $b) => (float) $a <=> (float) $b)->last()
            : $sizes->take(4)->implode(', ') . ($sizes->count() > 4 ? ' +' . ($sizes->count() - 4) : '');

        return trim($this->size_system . ' ' . $label);
    }

    /** Variant data for the detail page's size/colour picker. */
    public function variantMatrix(): array
    {
        return $this->variants->map(fn ($v) => [
            'size' => (string) $v->size,
            'color' => (string) $v->color,
            'available' => (bool) $v->is_available,
        ])->values()->all();
    }
}
