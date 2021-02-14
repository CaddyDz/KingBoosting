<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mirovit\NovaNotifications\Notification as Notif;

class OrderPlaced extends Notification implements ShouldQueue
{
	use Queueable;

	private Order $order;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(Order $order)
	{
		$this->order = $order;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param mixed $notifiable
	 * @return array
	 */
	public function via($notifiable): array
	{
		return ['database', 'broadcast'];
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param mixed $notifiable
	 * @return array
	 */
	public function toArray($notifiable): array
	{
		return Notif::make()
			->info(__('A new order has been placed, check it out'))
			->subtitle($this->order->purchase)
			->routeDetail('orders', $this->order->id)
			->toArray();
	}
}
