<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingPainPoint extends Model
{
    protected $fillable = [
        'marketing_audience_id',
        'title',
        'description',
        'severity',
        'business_impact',
        'desired_solution',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    public function audience(): BelongsTo
    {
        return $this->belongsTo(MarketingAudience::class, 'marketing_audience_id');
    }
}
