<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return auth()->check();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		// We need to verify that the price hasn't been tampered with
		// To do that we need to calculate the price here the same way we do in the frontend
		return [
			'service' => 'bail|required|exists:service_service_type,id',
			// Get list of service service types where current_tier is required
			// Current tier is required for every win boosting
			'current_tier' => 'required_if:service,1|exists:tiers,id',
			'current_division' => 'required_if:service,1|exists:divisions,id',
			'server' => 'required_if:service,1|exists:servers,id',
			'number_of_wins' => 'required_if:service,1|exists:win_boostings,id',
			// Solo/Duo or Flex 5v5
			'queue' => 'required_if:service,1|in:solo_duo,flex_5v5',
			'specific_champions' => 'required_if:service,1',
			'priority_order' => 'required_if:service,1',
			'with_streaming' => 'required_if:service,1',
			'price' => 'bail|required',
			'discount_code' => '',
			// Order details
			'in_game_name' => 'bail|required|string|min:3|max:100',
			'in_game_server' => 'bail|required|exists:servers,id',
			'comments' => 'nullable|string|min:5|max:200',
			'appear_offline' => 'bail|required|boolean',
			'booster_id' => 'nullable|exists:users,id',
			'spell_order' => 'bail|required|in:d,f',
			// End order details
			// Not any user, must be a booster
			// Automatically generated
			// 'purchase' => "Nothing",
		];
	}
}
