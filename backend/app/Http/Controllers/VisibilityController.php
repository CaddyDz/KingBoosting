<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class VisibilityController extends Controller
{
	/**
	 * A booster shows or hides themselves in the booster list and at checkout.
	 */
	public function __invoke(Request $request): UserResource
	{
		/** @var User $user */
		$user = $request->user();
		$this->authorize('updateVisibility', $user);

		$user->visible = $request->validate(['visible' => ['required', 'boolean']])['visible'];
		$user->save();

		return new UserResource($user);
	}
}
