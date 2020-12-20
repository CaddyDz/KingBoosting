<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BoostersController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
	// lists authed user
	Route::post('orders', [OrderController::class, 'store']);
});
// auth routes
Route::post('register', [RegisterController::class, 'register']);
Route::prefix('auth')->group(function () {
	Route::post('login', [LoginController::class, 'login'])->name('login');
	Route::middleware(['auth:sanctum'])->group(function () {
		// lists authed user
		Route::get('/user', [LoginController::class, 'user']);
		Route::post('logout', [LoginController::class, 'logout']);
	});
});
// List an array of boosters usernames
Route::get('/getBoostersNames', [BoostersController::class, 'getBoostersNames']);
Route::get('secret', [CheckoutController::class, 'intent']);
