<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferBooking extends Model
{
    protected $fillable = [
        'driver_name','contact_number','plate'
    ];
}
