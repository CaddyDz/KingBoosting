<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Fine
 */
class FineResource extends JsonResource
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
			'booster' => $this->whenLoaded('booster', fn (): array => ['id' => $this->booster_id, 'username' => $this->booster->username]),
			'amount_cents' => $this->amount_cents,
			'reason' => $this->reason,
			'settled' => $this->payout_id !== null,
			'payout_id' => $this->payout_id,
			'created_at' => $this->created_at,
		];
	}
}
