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
        'assigned_to', 'archived_at', 'read_at',
        'is_spam', 'spam_score', 'spam_reasons', 'ip_address',
    ];

    protected $casts = [
        'archived_at' => 'datetime',
        'read_at' => 'datetime',
        'is_spam' => 'boolean',
        'spam_reasons' => 'array',
    ];

    /** Junk stays out of the working list and out of the bell count. */
    public function scopeNotSpam($query)
    {
        return $query->where('is_spam', false);
    }

    public function scopeSpam($query)
    {
        return $query->where('is_spam', true);
    }

    /** Leads an admin has not opened yet — what the navbar bell counts. */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at')->whereNull('archived_at')->where('is_spam', false);
    }

    public function markAsRead(): void
    {
        if (! $this->read_at) {
            $this->forceFill(['read_at' => now()])->save();
        }
    }

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
