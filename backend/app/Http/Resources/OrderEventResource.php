<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\{OrderEvent, User};
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * One step of an order's timeline. Staff appear as "Support" to customers and boosters.
 *
 * @mixin OrderEvent
 */
class OrderEventResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		/** @var User $viewer */
		$viewer = $request->user();

		return [
			'id' => $this->id,
			'type' => $this->type->value,
			'from_status' => $this->from_status?->value,
			'to_status' => $this->to_status?->value,
			'actor' => $this->actorName($viewer),
			'created_at' => $this->created_at,
		];
	}

	private function actorName(User $viewer): string
	{
		$actor = $this->actor;

		return match (true) {
			$actor === null => 'King Boosting',
			$actor->isStaff() && ! $viewer->isStaff() => 'Support',
			default => $actor->username,
		};
	}
}
