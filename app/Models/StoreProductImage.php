<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One item of a product's gallery: a photo, or a video (type = video).
 * The table keeps its original name; videos were added later.
 */
class StoreProductImage extends Model
{
    public const TYPE_IMAGE = 'image';
    public const TYPE_VIDEO = 'video';

    protected $fillable = ['store_product_id', 'type', 'path', 'alt', 'is_primary', 'sort_order'];

    protected $attributes = [
        'type' => self::TYPE_IMAGE,
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(StoreProduct::class, 'store_product_id');
    }

    public function isVideo(): bool
    {
        return $this->type === self::TYPE_VIDEO;
    }

    public function getUrlAttribute(): ?string
    {
        return media_url($this->path);
    }

    /** Browser MIME type for a <source> element. */
    public function getMimeAttribute(): string
    {
        return match (strtolower(pathinfo($this->path, PATHINFO_EXTENSION))) {
            'webm' => 'video/webm',
            'mp4', 'm4v' => 'video/mp4',
            'png' => 'image/png',
            'webp' => 'image/webp',
            default => 'image/jpeg',
        };
    }
}
