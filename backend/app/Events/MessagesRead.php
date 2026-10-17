<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\{InteractsWithSockets, PrivateChannel};

/**
 * The customer or the booster read the order's chat up to a message: the other side can show it as seen.
 */
class MessagesRead implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets;

	public function __construct(public int $orderId, public int $readerId, public int $upToId) {}

	public function broadcastOn(): PrivateChannel
	{
		return new PrivateChannel("orders.{$this->orderId}");
	}

	public function broadcastAs(): string
	{
		return 'messages.read';
	}

	/**
	 * @return array{reader_id: int, up_to_id: int}
	 */
	public function broadcastWith(): array
	{
		return ['reader_id' => $this->readerId, 'up_to_id' => $this->upToId];
	}
}
