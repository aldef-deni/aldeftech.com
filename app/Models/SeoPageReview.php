<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoPageReview extends Model
{
    protected $fillable = ['blog_post_id', 'page_url', 'target_keyword', 'optimization_status', 'recommendations', 'analyzed_at', 'next_review_at'];

    protected $casts = ['recommendations' => 'array', 'analyzed_at' => 'datetime', 'next_review_at' => 'datetime'];

    public function post() { return $this->belongsTo(BlogPost::class, 'blog_post_id'); }
}
