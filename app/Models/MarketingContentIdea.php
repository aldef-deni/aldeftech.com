<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingContentIdea extends Model
{
    protected $fillable = [
        'marketing_campaign_id',
        'marketing_audience_id',
        'marketing_pain_point_id',
        'marketing_keyword_id',
        'marketing_content_pillar_id',
        'title',
        'hook',
        'brief',
        'content_type',
        'funnel_stage',
        'status',
        'priority',
        'platforms',
        'scheduled_at',
        'cta',
    ];

    protected $casts = [
        'platforms' => 'array',
        'scheduled_at' => 'datetime',
        'priority' => 'integer',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(MarketingCampaign::class, 'marketing_campaign_id');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(
            MarketingContent::class,
            'marketing_content_idea_id'
        );
    }

    public function audience(): BelongsTo
    {
        return $this->belongsTo(MarketingAudience::class, 'marketing_audience_id');
    }

    public function painPoint(): BelongsTo
    {
        return $this->belongsTo(MarketingPainPoint::class, 'marketing_pain_point_id');
    }

    public function keyword(): BelongsTo
    {
        return $this->belongsTo(MarketingKeyword::class, 'marketing_keyword_id');
    }

    public function contentPillar(): BelongsTo
    {
        return $this->belongsTo(MarketingContentPillar::class, 'marketing_content_pillar_id');
    }
}
