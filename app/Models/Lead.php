<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'company', 'email', 'whatsapp', 'project_type',
        'budget_range', 'message', 'status', 'source',
        'assigned_to', 'archived_at',
    ];

    protected $casts = [
        'archived_at' => 'datetime',
    ];

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function notes()
    {
        return $this->hasMany(LeadNote::class)->orderByDesc('created_at');
    }

    public function getStatusLabelAttribute(): string
    {
        return config('aldeftech.lead.statuses')[$this->status] ?? ucfirst($this->status);
    }

    public function getSourceLabelAttribute(): string
    {
        return config('aldeftech.lead.sources')[$this->source] ?? ucfirst($this->source);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }
}
