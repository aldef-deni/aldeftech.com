<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class ProcessStep extends Model
{
    use HasFactory, HasTranslations;

    /** Fields served per locale; see HasTranslations. */
    protected array $translatable = ['title', 'description'];

    protected $fillable = ['step_number', 'title', 'description', 'icon', 'sort_order', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('step_number');
    }
}
