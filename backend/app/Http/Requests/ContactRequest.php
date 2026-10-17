<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
	/**
	 * What a message is about. Booster applications have their own form.
	 */
	public const CATEGORIES = ['question', 'order', 'bug', 'other'];

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, list<mixed>>
	 */
	public function rules(): array
	{
		return [
			'category' => ['required', Rule::in(self::CATEGORIES)],
			'name' => ['required', 'string', 'max:64'],
			'email' => ['required', 'email', 'max:255'],
			'message' => ['required', 'string', 'min:10', 'max:5000'],
		];
	}
}
