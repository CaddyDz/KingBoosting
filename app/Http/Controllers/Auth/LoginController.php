<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Requests\Token;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
	/**
	 * details api
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function user(Request $request)
	{
		return $request->user();
	}

	/**
	 * login api
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function login(Token $request)
	{
		$user = User::where('email', $request->email)->first();

		if (! $user || ! Hash::check($request->password, $user->password)) {
			throw ValidationException::withMessages([
				'email' => ['The provided credentials are incorrect.'],
			]);
		}

		return $user->createToken('SPA')->plainTextToken;
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
}
