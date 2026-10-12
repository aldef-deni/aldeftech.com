<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreCategory extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'sort_order'];

    public function products()
    {
        return $this->hasMany(StoreProduct::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
