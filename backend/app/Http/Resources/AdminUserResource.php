<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A user as the admin's users screen shows them.
 *
 * @mixin User
 */
class AdminUserResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'username' => $this->username,
			'email' => $this->email,
			'role' => $this->role,
			'visible' => $this->visible,
			'orders_count' => $this->whenCounted('orders'),
			'jobs_count' => $this->whenCounted('jobs'),
			'created_at' => $this->created_at,
		];
	}
}
