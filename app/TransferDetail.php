<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferDetail extends Model
{
    protected $fillable = [
        'provider', 'transfer_detail_id'
    ];
}
