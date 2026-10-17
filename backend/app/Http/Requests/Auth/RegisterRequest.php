<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class RegisterRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, list<string|ValidationRule|Password>>
	 */
	public function rules(): array
	{
		return [
			'username' => ['required', 'string', 'min:3', 'max:32', 'alpha_dash:ascii', 'unique:users,username'],
			'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
			'password' => ['required', 'confirmed', Password::defaults()],
		];
	}
}
