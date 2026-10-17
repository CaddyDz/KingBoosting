<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Http\{Request, Response};
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
	/**
	 * Sign in with the session cookie. The route is throttled per email and IP.
	 */
	public function store(LoginRequest $request): UserResource
	{
		$request->authenticate();
		$request->session()->regenerate();

		return new UserResource($request->user());
	}

	public function destroy(Request $request): Response
	{
		Auth::guard('web')->logout();

		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return response()->noContent();
	}
}
