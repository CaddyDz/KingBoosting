<?php

declare(strict_types=1);

use App\Http\Controllers\MeController;
use App\Http\Controllers\Orders\{JobController, OrderController};
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{AuthenticatedSessionController, NewPasswordController, PasswordResetLinkController, RegisteredUserController};

/*
 * Everything here runs on Sanctum's stateful SPA auth: session cookie plus CSRF (docs/adr/0001).
 */

Route::post('register', [RegisteredUserController::class, 'store'])->middleware('throttle:register');
Route::post('login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:login');
Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('throttle:password-reset');
Route::post('reset-password', [NewPasswordController::class, 'store'])->middleware('throttle:password-reset');

Route::middleware('auth:sanctum')->group(function (): void {
	Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);
	Route::get('me', MeController::class);

	Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
	Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
	Route::get('jobs', [JobController::class, 'index'])->name('jobs.index');
});
