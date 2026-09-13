<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoOpportunity extends Model
{
    protected $fillable = ['fingerprint', 'category', 'cluster', 'topic', 'keyword', 'reason', 'priority', 'status', 'pillar_blog_post_id'];

    protected $casts = ['priority' => 'integer'];

}
