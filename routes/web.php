<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\{MessagesController, OrdersController};

Route::middleware(['auth:sanctum'])->group(function () {
	Route::post('orders', [OrdersController::class, 'store']);
	Route::post('messages/send', [MessagesController::class, 'store']);
});

Route::post('register', [RegisterController::class, 'register']);
