<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory, HasTranslations;

    /** Fields served per locale; see HasTranslations. */
    protected array $translatable = ['question', 'answer', 'category'];

    protected $fillable = ['question', 'answer', 'category', 'is_published', 'sort_order'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
