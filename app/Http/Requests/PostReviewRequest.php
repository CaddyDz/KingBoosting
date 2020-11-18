<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PostReviewRequest extends FormRequest
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
			'score' => 'required|numeric|between:1,5',
			'name' => auth()->check() ? 'nullable' : 'required' . '|string|min:3|max:50',
			'email' => auth()->check() ? 'nullable' : 'required' . '|email',
			'message' => 'required|string|max:5000',
			'listing_id' => 'required|exists:listings,id',
			'ip' => $this->ip_rules(),
		];
	}

	/**
	 * IP rules
	 *
	 * An array of validation rules
	 * Determines the unique check of listing & IP
	 *
	 * @return array
	 **/
	public function ip_rules(): array
	{
		return [
			'nullable',
			Rule::unique('reviews')->where(
				fn ($query) => $query->where('ip', request()->ip)
								->where('listing_id', request()->listing_id)
				),
		];
	}
}
