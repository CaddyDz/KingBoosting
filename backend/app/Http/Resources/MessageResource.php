<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A chat message. The same shape goes over the WebSocket, so it doesn't depend on who's reading:
 * staff always appear as "Support", and the client works out which messages are its own from `sender.id`.
 *
 * @mixin Message
 */
class MessageResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(?Request $request = null): array
	{
		return self::payload($this->resource);
	}

	/**
	 * @return array<string, mixed>
	 */
	public static function payload(Message $message): array
	{
		$sender = $message->sender;

		return [
			'id' => $message->id,
			'order_id' => $message->order_id,
			'body' => $message->body,
			'system' => $message->isSystem(),
			'sender' => $sender === null ? null : [
				'id' => $sender->id,
				'name' => $sender->isStaff() ? 'Support' : $sender->username,
				'role' => $sender->isStaff() ? 'support' : $sender->role->value,
			],
			'read_at' => $message->read_at?->toIso8601String(),
			'created_at' => $message->created_at?->toIso8601String(),
		];
	}
}
