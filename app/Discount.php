<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $casts = [
        'expires' => 'datetime',
    ];
}
