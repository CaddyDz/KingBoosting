<?php

declare(strict_types=1);

namespace App\Http\Requests\Orders;

use App\Models\Order;
use App\Pricing\Catalog;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * The order details around the quote. The quote's own inputs (ranks, amount, options) are checked by the
 * quote engine, and any price the client sends is never read.
 */
class StoreOrderRequest extends FormRequest
{
	public function authorize(): bool
	{
		return (bool) $this->user()?->can('create', Order::class);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, list<mixed>>
	 */
	public function rules(): array
	{
		$catalog = app(Catalog::class);
		$service = $catalog->find($this->string('service')->toString());

		return [
			'service' => ['required', 'string', Rule::in($catalog->keys())],
			'server' => ['required', 'string', Rule::in($service ? array_keys($catalog->servers($service->game)) : [])],
			'account_name' => ['required', 'string', 'max:64'],
			'account_login' => ['nullable', 'required_with:account_password', 'string', 'max:255'],
			'account_password' => ['nullable', 'required_with:account_login', 'string', 'max:255'],
			'comment' => ['nullable', 'string', 'max:1000'],
			// A booster picked at checkout must be a booster customers can see (the legacy rule took any username)
			'booster_id' => ['nullable', 'integer', Rule::exists('users', 'id')->where('role', 'booster')->where('visible', true)],
		];
	}

	/**
	 * What goes to the quote engine: everything but the order details above.
	 *
	 * @return array<string, mixed>
	 */
	public function quoteInput(): array
	{
		return $this->except(['service', 'server', 'account_name', 'account_login', 'account_password', 'comment', 'booster_id']);
	}
}
