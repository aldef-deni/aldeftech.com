<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreProductImage extends Model
{
    protected $fillable = ['store_product_id', 'path', 'alt', 'is_primary', 'sort_order'];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(StoreProduct::class, 'store_product_id');
    }

    public function getUrlAttribute(): ?string
    {
        return media_url($this->path);
    }
}
