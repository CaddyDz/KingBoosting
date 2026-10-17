<?php

declare(strict_types=1);

use App\Http\Controllers\MeController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\{BoosterController, NotificationController};
use App\Http\Controllers\Chat\MessageController;
use App\Http\Controllers\Pricing\{CatalogController, QuoteController};
use App\Http\Controllers\Orders\{JobController, OrderActionController, OrderController};
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{AuthenticatedSessionController, NewPasswordController, PasswordResetLinkController, RegisteredUserController};

/*
 * Everything here runs on Sanctum's stateful SPA auth: session cookie plus CSRF (docs/adr/0001).
 */

Route::post('register', [RegisteredUserController::class, 'store'])->middleware('throttle:register');
Route::post('login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:login');
Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('throttle:password-reset');
Route::post('reset-password', [NewPasswordController::class, 'store'])->middleware('throttle:password-reset');

Route::post('quotes', [QuoteController::class, 'store'])->middleware('throttle:quotes')->name('quotes.store');
Route::get('catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('catalog/{service}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('boosters', [BoosterController::class, 'index'])->name('boosters.index');

Route::middleware('auth:sanctum')->group(function (): void {
	Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);
	Route::get('me', MeController::class);

	Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
	Route::post('notifications/read', [NotificationController::class, 'readAll'])->name('notifications.read-all');
	Route::post('notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');

	Route::post('users/{user}/notices', [NoticeController::class, 'store'])->name('users.notices.store');

	Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
	Route::post('orders', [OrderController::class, 'store'])->middleware('throttle:checkout')->name('orders.store');
	Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
	Route::get('jobs', [JobController::class, 'index'])->name('jobs.index');

	Route::get('orders/{order}/messages', [MessageController::class, 'index'])->name('orders.messages.index');
	Route::post('orders/{order}/messages', [MessageController::class, 'store'])->middleware('throttle:messages')->name('orders.messages.store');
	Route::post('orders/{order}/messages/read', [MessageController::class, 'read'])->name('orders.messages.read');

	Route::controller(OrderActionController::class)->prefix('orders/{order}')->name('orders.')->group(function (): void {
		Route::post('claim', 'claim')->name('claim');
		Route::post('assign', 'assign')->name('assign');
		Route::post('pause', 'pause')->name('pause');
		Route::post('resume', 'resume')->name('resume');
		Route::post('release', 'release')->name('release');
		Route::post('complete', 'complete')->name('complete');
		Route::post('mark-paid-out', 'markPaidOut')->name('mark-paid-out');
		Route::put('credentials', 'updateCredentials')->name('credentials');
	});
});
