<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingKeyword extends Model
{
    protected $fillable = [
        'marketing_audience_id',
        'keyword',
        'slug',
        'search_intent',
        'keyword_type',
        'search_volume',
        'keyword_difficulty',
        'notes',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'search_volume' => 'integer',
        'keyword_difficulty' => 'decimal:2',
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    public function audience(): BelongsTo
    {
        return $this->belongsTo(MarketingAudience::class, 'marketing_audience_id');
    }
}
