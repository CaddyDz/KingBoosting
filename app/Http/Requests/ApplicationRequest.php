<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'summoner' => 'bail|required|string|max:50|unique:applications',
			'email' => 'bail|required|email|unique:applications',
			'country' => 'bail|required|string|min:2|max:50',
			'discord_skype' => 'bail|required|string|unique:applications',
			'rank' => 'bail|required|string',
			'languages' => 'bail|required|string',
			'games' => 'bail|required|string',
			'servers' => 'bail|required|string',
			'hours' => 'bail|required|string',
			'application' => 'bail|required|string|min:25|max:10000'
		];
	}
}
