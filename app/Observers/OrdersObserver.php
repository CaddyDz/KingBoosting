<?php

namespace App\Observers;

use App\Order;

class OrdersObserver
{
    public function creating(Order $order)
    {
        $order->client_id = auth()->id();
    }
}
