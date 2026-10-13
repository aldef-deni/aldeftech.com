<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * A shoe category. One level of nesting: a top-level category may have
 * subcategories, a subcategory may not have its own.
 */
class StoreCategory extends Model
{
    protected $fillable = ['parent_id', 'name', 'slug', 'description', 'sort_order'];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order')->orderBy('name');
    }

    public function products()
    {
        return $this->hasMany(StoreProduct::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /** "Sepatu Lari › Trail" for a subcategory, the name for a top-level one. */
    public function label(): string
    {
        return $this->parent ? $this->parent->name . ' › ' . $this->name : $this->name;
    }

    /** This category and its subcategories: what a filter on it should match. */
    public function familyIds(): array
    {
        return array_merge([$this->id], $this->children()->pluck('id')->all());
    }

    /**
     * Every category as [id => label] in tree order, subcategories indented
     * under their parent. Used by the select boxes in the admin.
     *
     * @return array<int, string>
     */
    public static function treeOptions(): array
    {
        $options = [];

        foreach (static::tree() as $category) {
            $options[$category->id] = $category->name;
            foreach ($category->children as $child) {
                $options[$child->id] = '— ' . $child->name;
            }
        }

        return $options;
    }

    /** @return Collection<int, self> top-level categories with their children loaded */
    public static function tree(): Collection
    {
        return static::topLevel()->ordered()->with('children')->get();
    }
}
