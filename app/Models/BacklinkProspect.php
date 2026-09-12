<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BacklinkProspect extends Model
{
    protected $fillable = ['domain', 'url', 'website_name', 'category', 'relevance_score', 'authority_score', 'traffic_score', 'metrics_provider', 'spam_risk', 'contact_name', 'contact_email', 'contact_url', 'target_blog_post_id', 'suggested_anchor', 'suggested_pitch', 'status', 'notes', 'evidence', 'verified_at'];

    protected $casts = ['evidence' => 'array', 'verified_at' => 'datetime', 'relevance_score' => 'integer'];

    public const STATUSES = ['new', 'reviewed', 'approved', 'contacted', 'won', 'rejected'];

    public function post() { return $this->belongsTo(BlogPost::class, 'target_blog_post_id'); }

    public function outreach() { return $this->hasOne(SeoOutreachDraft::class); }
}
