<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return auth()->check();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'booster' => 'nullable|exists:users,username',
			'purchase' => 'required|string|min:3|max:100',
			'service' => 'requried|string|in:Division Boosting,Ranked Win Boosting,Duo Queue Boosting,Placement Boosting,Champion Mastery,Clash Boosting,Normal matches,Account leveling,Rank Boosting,Placement Matches,Competitive Win Boosting,Unrated Matches,Ranked Win Boosting,Rank Boosting,Placement Matches,Normal Matches',
			'nickname' => 'required|string|min:3|max:16',
			'server' => 'required|string|in:North America,EU-West,EU-Nordic & East,Turkey,Russia,Brazil,Latin America North,Latin America South,Oceania,PBE',
			'options' => 'required|array',
			'comment' => 'nullable|string|min:3|max:1000'
		];
	}
}
