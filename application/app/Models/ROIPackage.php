<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ROIPackage extends Model
{
    protected $table = 'roi_packages';

    protected $fillable = [
        'min_amount',
        'max_amount',
        'cap_multiplier',
        'status'
    ];
}