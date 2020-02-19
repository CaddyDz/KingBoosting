<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function client()
    {
        return $this->belongsTo(User::class, 'id', 'client_id');
    }
}
