<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/** A shoe brand in Aldef Store. Independent of categories. */
class StoreBrand extends Model
{
    protected $fillable = ['name', 'slug', 'logo', 'description', 'sort_order'];

    public function products()
    {
        return $this->hasMany(StoreProduct::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * The brand with this name, matched case-insensitively, created when it
     * does not exist yet. Used by the ZIP import.
     */
    public static function findOrCreateByName(string $name): self
    {
        $name = trim($name);

        return static::whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->first()
            ?? static::create(['name' => $name, 'slug' => static::uniqueSlug($name)]);
    }

    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'brand';
        $slug = $base;
        $n = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
    }
}
