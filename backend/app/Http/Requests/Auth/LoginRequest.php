<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, list<string>>
	 */
	public function rules(): array
	{
		return [
			'email' => ['required', 'string', 'email'],
			'password' => ['required', 'string'],
			'remember' => ['sometimes', 'boolean'],
		];
	}

	/**
	 * Log the user in on the session guard, or fail with a generic message.
	 *
	 * @throws ValidationException
	 */
	public function authenticate(): void
	{
		if (! Auth::guard('web')->attempt($this->only('email', 'password'), $this->boolean('remember'))) {
			throw ValidationException::withMessages([
				'email' => __('auth.failed'),
			]);
		}
	}
}
