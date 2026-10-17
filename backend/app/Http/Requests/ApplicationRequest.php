<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Application;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
	public function authorize(): bool
	{
		return Gate::allows('create', Application::class);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, list<string>>
	 */
	public function rules(): array
	{
		return [
			'in_game_name' => ['required', 'string', 'max:64'],
			'email' => ['required', 'email', 'max:255'],
			'country' => ['required', 'string', 'max:64'],
			'discord_skype' => ['required', 'string', 'max:64'],
			'rank' => ['required', 'string', 'max:64'],
			'languages' => ['required', 'string', 'max:255'],
			'games' => ['required', 'string', 'max:255'],
			'servers' => ['required', 'string', 'max:255'],
			'hours' => ['required', 'string', 'max:64'],
			'motivation' => ['required', 'string', 'min:25', 'max:5000'],
		];
	}

	/**
	 * Field names as the form shows them, for the error messages.
	 *
	 * @return array<string, string>
	 */
	public function attributes(): array
	{
		return [
			'in_game_name' => 'in-game name',
			'discord_skype' => 'Discord or Skype',
			'hours' => 'availability',
		];
	}
}
