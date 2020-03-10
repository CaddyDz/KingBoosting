<?php

namespace App\Providers;

use App\Order;
use App\Observers\OrdersObserver;
use Illuminate\Support\ServiceProvider;

class ObserversServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Order::observe(OrdersObserver::class);
    }
}
