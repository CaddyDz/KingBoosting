<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\{Order, User};

/**
 * Sent to the customer when Stripe declines their payment.
 */
class PaymentFailed extends OrderNotification
{
	public function __construct(Order $order, public string $reason)
	{
		parent::__construct($order);
	}

	public function title(): string
	{
		return 'Your payment didn\'t go through';
	}

	public function body(User $notifiable): string
	{
		return "{$this->reason} Your order for {$this->order->title} is saved: try again with another card.";
	}

	protected function url(): string
	{
		return config('app.frontend_url')."/checkout/{$this->order->id}";
	}
}
