<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BoostersController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

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
	Route::get('auth/user', [LoginController::class, 'user']);
	Route::post('auth/logout', [LoginController::class, 'logout']);
	Route::post('orders', [OrderController::class, 'store']);
});
// auth routes
Route::post('auth/login', [LoginController::class, 'login']);
Route::post('auth/register', [RegisterController::class, 'register']);
// List an array of boosters usernames
Route::get('/getBoostersNames', [BoostersController::class, 'getBoostersNames']);