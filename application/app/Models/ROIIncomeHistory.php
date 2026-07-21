<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ROIIncomeHistory extends Model
{
    protected $table = 'roi_income_history';

    protected $fillable = [
    'investment_id',
    'user_id',
    'roi_percent',
    'roi_amount',
    'cycle_no',
    'roi_date',
    'remarks',
];
}