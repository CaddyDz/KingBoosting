<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\{MessagesController, OrderController};

Route::middleware(['auth:sanctum'])->group(function () {
	Route::post('orders', [OrderController::class, 'store']);
	Route::post('messages/send', [MessagesController::class, 'store']);
});

Route::post('register', [RegisterController::class, 'register']);
