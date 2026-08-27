<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Portfolio extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    /** Fields served per locale; see HasTranslations. */
    protected array $translatable = ['title', 'short_description', 'description', 'challenge', 'approach', 'solution', 'results'];

    protected $fillable = [
        'title', 'slug', 'short_description', 'description',
        'category_id', 'client', 'year', 'featured_image',
        'technologies', 'project_url', 'challenge', 'approach',
        'solution', 'results', 'is_featured', 'is_published',
        'published_at', 'meta_title', 'meta_description', 'sort_order',
    ];

    protected $casts = [
        'technologies' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Portfolio $model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(PortfolioCategory::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(PortfolioImage::class)->orderBy('sort_order');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('published_at', 'desc');
    }
}
