<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\User;

/**
 * Sent to a booster who gets an order without claiming it: the customer picked them, or staff assigned it.
 */
class OrderAssigned extends OrderNotification
{
	public function title(): string
	{
		return 'You have a new order';
	}

	public function body(User $notifiable): string
	{
		return "{$this->order->title} ({$this->order->game->label()}) is yours. Message the customer to agree on a start time.";
	}
}
