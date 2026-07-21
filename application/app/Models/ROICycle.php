<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ROICycle extends Model
{
    protected $table = 'roi_cycles';

    protected $fillable = [
        'cycle_no',
        'daily_roi',
        'duration_days',
        'is_lifetime',
        'status'
    ];
}