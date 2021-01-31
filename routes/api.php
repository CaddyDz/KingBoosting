<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\{BoostersController, CheckoutController, OrderController, UsersController};

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

Route::get('/user', fn (Request $request) => $request->user());
// List an array of boosters usernames
Route::get('/getBoostersNames', [BoostersController::class, 'getBoostersNames']);
// Get boosters
Route::get('/boosters', [BoostersController::class, 'index']);
Route::get('secret', [CheckoutController::class, 'intent']);
Route::post('apply', [BoostersController::class, 'apply']);
Route::post('contact', [ContactController::class, 'send']);
