<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoGrowthRun extends Model
{
    protected $fillable = ['run_key', 'task', 'status', 'result', 'error_message'];

    protected $casts = ['result' => 'array'];

}
