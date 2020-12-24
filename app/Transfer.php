<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = [
        'transfer_id','first_activity_id','next_activity_id','is_used'
    ];
}
