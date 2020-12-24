<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityBooking extends Model
{
    protected $fillable = [
        'activity_id', 'user_id', 'is_used'
    ];
}
