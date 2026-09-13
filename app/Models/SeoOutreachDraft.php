<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoOutreachDraft extends Model
{
    protected $fillable = ['backlink_prospect_id', 'subject', 'body', 'guest_post', 'status'];

    protected $casts = ['guest_post' => 'array'];

    public function prospect() { return $this->belongsTo(BacklinkProspect::class, 'backlink_prospect_id'); }
}
