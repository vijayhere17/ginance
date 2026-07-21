<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ROICronLog extends Model
{
    protected $table = 'roi_cron_logs';

    protected $fillable = [
        'cron_date',
        'processed',
        'success',
        'failed',
        'remarks'
    ];
}