<?php

declare(strict_types=1);

use App\Http\Controllers\OrderController;

Route::middleware(['auth:sanctum'])->group(function () {
	Route::post('orders', [OrderController::class, 'store']);
});
