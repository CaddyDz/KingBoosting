<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\{Order, User};

/**
 * Sent to the people on an order (never the one who acted) when its status changes.
 * The text is the same line the order's chat shows.
 */
class OrderStatusChanged extends OrderNotification
{
	/**
	 * The status when it changed: the notification is queued, and the order is reloaded when it's sent,
	 * by which time it may have moved on again.
	 */
	public string $status;

	public function __construct(Order $order, public string $summary)
	{
		parent::__construct($order);
		$this->status = $order->status->label();
	}

	public function title(): string
	{
		return "Order #{$this->order->id}: {$this->status}";
	}

	public function body(User $notifiable): string
	{
		return "{$this->order->title}: {$this->summary}";
	}
}
