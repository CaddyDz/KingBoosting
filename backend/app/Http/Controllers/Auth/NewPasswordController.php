<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\{JsonResponse, Request};
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password as PasswordRule;

class NewPasswordController extends Controller
{
	public function store(Request $request): JsonResponse
	{
		$request->validate([
			'token' => ['required'],
			'email' => ['required', 'email'],
			'password' => ['required', 'confirmed', PasswordRule::defaults()],
		]);

		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
			function (User $user) use ($request): void {
				$user->forceFill([
					'password' => $request->string('password')->toString(),
					'remember_token' => Str::random(60),
				])->save();

				event(new PasswordReset($user));
			},
		);

		if ($status !== Password::PASSWORD_RESET) {
			throw ValidationException::withMessages(['email' => [__($status)]]);
		}

		return response()->json(['message' => __($status)]);
	}
}
