<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Payout
 */
class PayoutResource extends JsonResource
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
			'gross_cents' => $this->gross_cents,
			'fines_cents' => $this->fines_cents,
			'amount_cents' => $this->amount_cents,
			'orders_count' => $this->orders_count,
			'paid_at' => $this->paid_at,
		];
	}
}
