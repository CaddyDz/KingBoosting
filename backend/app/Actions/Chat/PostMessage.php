<?php

declare(strict_types=1);

namespace App\Actions\Chat;

use App\Events\MessageSent;
use Illuminate\Support\Facades\Gate;
use App\Models\{Message, Order, User};

class PostMessage
{
	/**
	 * Post in the order's chat as the given user, or as the system when $sender is null.
	 */
	public function handle(?User $sender, Order $order, string $body): Message
	{
		if ($sender !== null) {
			Gate::forUser($sender)->authorize('chat', $order);
		}

		$message = new Message(['body' => $body]);
		$message->order()->associate($order);
		$message->sender()->associate($sender);
		$message->save();

		// The sender's own tab already shows the message, so only the others get it over the socket
		broadcast(new MessageSent($message->load('sender')))->toOthers();

		return $message;
	}
}
