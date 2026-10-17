<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\User;

/**
 * Sent to every booster when a paid order lands on the jobs board.
 */
class NewOrderAvailable extends OrderNotification
{
	public function title(): string
	{
		return 'A new order is waiting for a booster';
	}

	public function body(User $notifiable): string
	{
		return "{$this->order->title} ({$this->order->game->label()}) is on the jobs board.";
	}

	protected function url(): string
	{
		return config('app.frontend_url').'/dashboard/jobs';
	}
}
