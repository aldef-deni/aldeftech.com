<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One size/colour combination of a product. An empty size or colour means the
 * product has no such option, so a shoe sold in one colour only has variants
 * with color = ''.
 */
class StoreProductVariant extends Model
{
    protected $fillable = ['store_product_id', 'size', 'color', 'is_available', 'sort_order'];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(StoreProduct::class, 'store_product_id');
    }
}
