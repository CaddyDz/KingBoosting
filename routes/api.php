<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{BoostersController, CheckoutController, OrderController};

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
	Route::post('orders', [OrderController::class, 'store']);
});
// List an array of boosters usernames
Route::get('/getBoostersNames', [BoostersController::class, 'getBoostersNames']);
Route::get('secret', [CheckoutController::class, 'intent']);
Route::post('apply', [BoostersController::class, 'apply']);
