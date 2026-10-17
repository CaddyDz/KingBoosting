<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Notifications\DatabaseNotification;

/**
 * @mixin DatabaseNotification
 */
class NotificationResource extends JsonResource
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
			'title' => $this->data['title'] ?? '',
			'body' => $this->data['body'] ?? '',
			'order_id' => $this->data['order_id'] ?? null,
			'url' => $this->data['url'] ?? null,
			'read_at' => $this->read_at,
			'created_at' => $this->created_at,
		];
	}
}
