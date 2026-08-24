<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CeoProfile extends Model
{
    protected $fillable = [
        'name', 'position', 'profile_photo', 'short_bio', 'full_bio',
        'skills', 'experience', 'linkedin', 'github', 'instagram',
        'email', 'is_active',
    ];

    protected $casts = [
        'skills' => 'array',
        'experience' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
