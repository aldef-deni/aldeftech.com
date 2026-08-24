<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationMenu extends Model
{
    protected $fillable = [
        'label', 'url', 'parent_id', 'sort_order',
        'is_active', 'open_in_new_tab', 'location',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_in_new_tab' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(NavigationMenu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(NavigationMenu::class, 'parent_id');
    }

    public function scopeMain($query)
    {
        return $query->where('location', 'main')->where('is_active', true)->orderBy('sort_order');
    }
}
