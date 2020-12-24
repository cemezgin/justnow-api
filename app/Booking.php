<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'activity_booking_id', 'transfer_booking_id', 'qr_code'
    ];

    public function tourCategory()
    {
        return $this->hasOne(TourCategory::class,'tour_category_id');
    }
}
