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
<<<<<<< HEAD
        'platform_posts',
        'distribution_checklist',
=======
>>>>>>> a4f16a9b4c3a6b5d155af1e7ac7ccd6e601bdec4
        'content_type',
        'funnel_stage',
        'status',
        'ai_model',
        'ai_prompt_version',
        'generated_at',
        'approved_at',
        'published_at',
<<<<<<< HEAD
        'published_blog_post_id',
    ];

    protected $casts = [
        'platform_posts' => 'array',
        'distribution_checklist' => 'array',
=======
    ];

    protected $casts = [
>>>>>>> a4f16a9b4c3a6b5d155af1e7ac7ccd6e601bdec4
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
<<<<<<< HEAD

    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(
            BlogPost::class,
            'published_blog_post_id'
        );
    }
=======
>>>>>>> a4f16a9b4c3a6b5d155af1e7ac7ccd6e601bdec4
}
