<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\{Exceptions, Middleware};

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		web: __DIR__.'/../routes/web.php',
		api: __DIR__.'/../routes/api.php',
		commands: __DIR__.'/../routes/console.php',
		channels: __DIR__.'/../routes/channels.php',
		health: '/up',
	)
	->withMiddleware(function (Middleware $middleware): void {
		//
	})
	->withExceptions(function (Exceptions $exceptions): void {
		$exceptions->shouldRenderJsonWhen(
			fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
		);
	})->create();
