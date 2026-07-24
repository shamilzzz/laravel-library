<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibrarySetting extends Model
{
    protected $fillable = [
        'max_borrow_days',
        'max_borrow_limit',
        'borrow_charge',
        'late_fee_per_day',
    ];
}