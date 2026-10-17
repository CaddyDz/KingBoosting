<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\{Exceptions, Middleware};

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		web: __DIR__.'/../routes/web.php',
		api: __DIR__.'/../routes/api.php',
		commands: __DIR__.'/../routes/console.php',
		channels: __DIR__.'/../routes/channels.php',
		health: '/up',
		// Outside the web and api groups: Stripe has no session or CSRF token
		then: function (): void {
			Route::middleware([])->group(__DIR__.'/../routes/stripe.php');
			// Shortcuts for end-to-end tests and keyless checkout: never in production
			if (app()->environment(['local', 'testing'])) {
				Route::middleware('api')->prefix('api/testing')->group(__DIR__.'/../routes/testing.php');
			}
		},
	)
	->withMiddleware(function (Middleware $middleware): void {
		// Sanctum SPA auth: requests from SANCTUM_STATEFUL_DOMAINS get the session and CSRF checks
		$middleware->statefulApi();
		// Traefik (or the dev proxy) terminates TLS in front of nginx
		$middleware->trustProxies(at: ['10.0.0.0/8', '172.16.0.0/12', '192.168.0.0/16']);
	})
	->withExceptions(function (Exceptions $exceptions): void {
		$exceptions->shouldRenderJsonWhen(
			fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
		);
	})->create();
