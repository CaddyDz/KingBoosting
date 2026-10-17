<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\{JsonResponse, Request};

class PasswordResetLinkController extends Controller
{
	/**
	 * Email a reset link. The answer is the same whether or not the address has an account.
	 */
	public function store(Request $request): JsonResponse
	{
		$request->validate(['email' => ['required', 'email']]);

		Password::sendResetLink($request->only('email'));

		return response()->json(['message' => __('If that address has an account, we sent it a reset link.')]);
	}
}
