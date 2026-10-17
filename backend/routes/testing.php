<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Testing\SimulationController;

/*
 * Loaded only when APP_ENV is local or testing (bootstrap/app.php), under /api/testing. Never in production.
 */

Route::post('reset', [SimulationController::class, 'reset'])->name('testing.reset');
Route::post('orders/{order}/pay', [SimulationController::class, 'pay'])->middleware('auth:sanctum')->name('testing.orders.pay');
