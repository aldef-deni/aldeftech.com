<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingAudience extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'industry',
        'company_size',
        'decision_maker',
        'description',
        'goals',
        'needs',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    public function painPoints(): HasMany
    {
        return $this->hasMany(MarketingPainPoint::class);
    }

    public function keywords(): HasMany
    {
        return $this->hasMany(MarketingKeyword::class);
    }
}
