<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\RegisterRequest;

class RegisteredUserController extends Controller
{
	/**
	 * Create a customer account and sign them in. Nobody can register as anything else.
	 */
	public function store(RegisterRequest $request): JsonResponse
	{
		$user = User::query()->create($request->safe()->only(['username', 'email', 'password']));

		event(new Registered($user));

		Auth::guard('web')->login($user);
		$request->session()->regenerate();

		return (new UserResource($user->fresh()))->response()->setStatusCode(201);
	}
}
