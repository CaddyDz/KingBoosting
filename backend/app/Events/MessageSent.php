<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Message;
use Illuminate\Queue\SerializesModels;
use App\Http\Resources\MessageResource;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * A new message in an order's chat, pushed to everyone on the order's private channel.
 */
class MessageSent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public function __construct(public Message $message) {}

	public function broadcastOn(): PrivateChannel
	{
		return new PrivateChannel("orders.{$this->message->order_id}");
	}

	public function broadcastAs(): string
	{
		return 'message.sent';
	}

	/**
	 * @return array<string, mixed>
	 */
	public function broadcastWith(): array
	{
		return MessageResource::payload($this->message->loadMissing('sender'));
	}
}
