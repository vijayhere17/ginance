<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInvestment extends Model
{
    protected $table = 'user_investments';

    protected $fillable = [
    'user_id',
    'staked_user_id',
    'investment_no',
    'amount',
    'package_id',
    'cap_multiplier',
    'maximum_income',
    'total_roi_paid',
    'remaining_income',
    'current_cycle',
    'current_roi_percent',
    'cycle_start_date',
    'cycle_end_date',
    'days_completed',
    'status',
    'completed_at'
];
}