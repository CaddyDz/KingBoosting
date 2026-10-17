<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminUserResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
	public function index(Request $request): AnonymousResourceCollection
	{
		$this->authorize('viewAny', User::class);
		$validated = $request->validate([
			'role' => ['sometimes', Rule::enum(Role::class)],
			'search' => ['sometimes', 'nullable', 'string', 'max:100'],
		]);

		$users = User::query()
			->withCount(['orders', 'jobs'])
			->when($validated['role'] ?? null, fn ($query, string $role) => $query->where('role', $role))
			->when($validated['search'] ?? null, fn ($query, string $search) => $query->where(
				fn ($query) => $query->where('username', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"),
			))
			->orderBy('username')
			->paginate(20)
			->withQueryString();

		return AdminUserResource::collection($users);
	}

	/**
	 * Change a user's role or a booster's visibility.
	 */
	public function update(Request $request, User $user): AdminUserResource
	{
		$validated = $request->validate([
			'role' => ['sometimes', Rule::enum(Role::class)],
			'visible' => ['sometimes', 'boolean'],
		]);

		if (array_key_exists('role', $validated)) {
			$this->authorize('updateRole', $user);
			$user->role = Role::from($validated['role']);
		}
		if (array_key_exists('visible', $validated)) {
			// Only boosters have a visibility setting
			$this->authorize('updateVisibility', $user);
			$user->visible = (bool) $validated['visible'];
		}
		$user->save();

		return new AdminUserResource($user->loadCount(['orders', 'jobs']));
	}
}
