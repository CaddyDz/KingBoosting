<?php

declare(strict_types=1);

use App\Http\Controllers\{MessagesController, OrderController};

Route::middleware(['auth:sanctum'])->group(function () {
	Route::post('orders', [OrderController::class, 'store']);
	Route::post('messages/send', [MessagesController::class, 'store']);
});
