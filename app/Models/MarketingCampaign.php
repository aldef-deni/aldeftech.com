<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingCampaign extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'objective',
        'target_audiences',
        'platforms',
        'funnel_strategy',
        'start_date',
        'end_date',
        'status',
        'priority',
    ];

    protected $casts = [
        'target_audiences' => 'array',
        'platforms' => 'array',
        'funnel_strategy' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'priority' => 'integer',
    ];

    public function contentIdeas(): HasMany
    {
        return $this->hasMany(MarketingContentIdea::class);
    }
}
