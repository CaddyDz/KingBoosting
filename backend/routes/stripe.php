<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Stripe\WebhookController;

/*
 * Stripe calls this server to server: no session, no CSRF. The signature check is the authentication.
 */

Route::post('stripe/webhook', WebhookController::class)->name('stripe.webhook');
