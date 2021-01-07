<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\{Channel, InteractsWithSockets, PresenceChannel, PrivateChannel};

class SendMessage implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $message;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($message)
	{
		$this->message = $message;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return \Illuminate\Broadcasting\Channel|array
	 */
	public function broadcastOn()
	{
		return new PrivateChannel('channel.messages.' . $this->message->recepient_id);
	}
}
