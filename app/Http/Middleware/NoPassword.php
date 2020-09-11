<?php

namespace App\Http\Middleware;

use Closure;

class NoPassword
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		// If the user already has a password set, return a forbidden response
		if ($request->user()->password !== null) {
			return response([
				'status' => __('forbidden')
			], 403);
		}
		return $next($request);
	}
}
