<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIContentRun extends Model
{
    protected $table = 'ai_content_runs';

    protected $fillable = [
        'blog_post_id', 'category_id', 'topic', 'status', 'error_message',
        'started_at', 'completed_at',
    ];

    protected $casts = ['started_at' => 'datetime', 'completed_at' => 'datetime'];

    public function post()
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }
}
