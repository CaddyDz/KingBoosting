<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/login/{token}', [LoginController::class, 'authenticateNova']);

Route::prefix('auth')->group(function () {
	Route::middleware(['auth:sanctum'])->group(function () {
		// lists authed user
		Route::get('/user', [LoginController::class, 'user']);
	});
});
