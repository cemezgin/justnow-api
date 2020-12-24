<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'name', 'tour_category_id', 'duration','lat','long','location','buy_price','rate'
    ];

    public function tourCategory()
    {
        return $this->hasOne(TourCategory::class,'tour_category_id');
    }
}
