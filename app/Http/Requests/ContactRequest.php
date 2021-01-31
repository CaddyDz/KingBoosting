<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
	/**
	 * The URI to redirect to if validation fails.
	 *
	 * @var string
	 */
	protected $redirect = '/contact#form';

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name' => 'bail|required|string|min:3|max:50',
			'email' => 'bail|required|email',
			'subject' => 'bail|required|string|min:5|max:100',
			'message' => 'bail|required|string|min:10|max:5000',
		];
	}
}
