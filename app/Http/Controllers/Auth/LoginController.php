<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Requests\TokenRequest;
use App\Http\Controllers\Controller;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\{Auth, Hash};
use Illuminate\Validation\ValidationException;
use Illuminate\Http\{RedirectResponse, Request, Response};

class LoginController extends Controller
{
	/**
	 * details api
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function user(Request $request)
	{
		return response(['user' => $request->user()]);
	}

	/**
	 * login api
	 *
	 * @param \App\Http\Requests\TokenRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function login(TokenRequest $request)
	{
		$user = User::where('email', $request->email)->first();

		if (!$user || !Hash::check($request->password, $user->password)) {
			throw ValidationException::withMessages([
				'email' => ['The provided credentials are incorrect.'],
			]);
		}
		return response(['token' => $user->createToken('SPA')->plainTextToken, 'user' => $user]);
	}

	/**
	 * Logout user
	 *
	 * Revoke all tokens of user
	 *
	 * @param \Illuminate\Http\Request $request Request object
	 * @return \Illuminate\Http\Response
	 **/
	public function logout(Request $request): Response
	{
		// Revoke all tokens...
		$request->user()->tokens()->delete();
		return response([
			'status' => 'Logged out',
		], 204);
	}

	public function register(Request $request)
	{
		$user = new User([
			'name' => $request->first_name . ' ' . $request->last_name,
			'email' => $request->email,
			'password' => bcrypt($request->password)
		]);
		$user->save();
		return $user->createToken('SPA')->plainTextToken;
	}

	public function authenticateNova(string $token): RedirectResponse
	{
		// Get user from token
		$user = PersonalAccessToken::findToken($token)
			->tokenable()
			->first();
		Auth::login($user);
		// Authenticate that user
		return redirect(config('nova.path'));
	}
}
