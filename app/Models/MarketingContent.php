<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingContent extends Model
{
    protected $fillable = [
        'marketing_content_idea_id',
        'marketing_campaign_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'content_type',
        'funnel_stage',
        'status',
        'ai_model',
        'ai_prompt_version',
        'generated_at',
        'approved_at',
        'published_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'approved_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function idea(): BelongsTo
    {
        return $this->belongsTo(
            MarketingContentIdea::class,
            'marketing_content_idea_id'
        );
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(
            MarketingCampaign::class,
            'marketing_campaign_id'
        );
    }
}
