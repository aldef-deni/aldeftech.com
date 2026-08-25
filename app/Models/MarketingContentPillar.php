<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingContentPillar extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'objectives',
        'content_types',
        'funnel_stage',
        'default_priority',
        'default_frequency',
        'is_active',
    ];

    protected $casts = [
        'default_priority' => 'integer',
        'default_frequency' => 'integer',
        'is_active' => 'boolean',
    ];
}
