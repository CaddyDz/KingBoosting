<?php

declare(strict_types=1);

namespace App\Http\Requests\Orders;

use Illuminate\Validation\Rule;
use App\Enums\{Game, OrderStatus};
use Illuminate\Foundation\Http\FormRequest;

class ListOrdersRequest extends FormRequest
{
	/**
	 * Accept `?status=pending,completed` as well as `?status[]=pending`.
	 */
	protected function prepareForValidation(): void
	{
		if (is_string($this->input('status'))) {
			$this->merge(['status' => array_filter(explode(',', $this->string('status')->toString()))]);
		}
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, list<mixed>>
	 */
	public function rules(): array
	{
		return [
			'status' => ['sometimes', 'array'],
			'status.*' => [Rule::enum(OrderStatus::class)],
			'game' => ['sometimes', Rule::enum(Game::class)],
			'service' => ['sometimes', 'string', 'max:64'],
			'search' => ['sometimes', 'nullable', 'string', 'max:100'],
			'sort' => ['sometimes', Rule::in(['newest', 'oldest'])],
			'per_page' => ['sometimes', 'integer', 'min:1', 'max:50'],
		];
	}
}
