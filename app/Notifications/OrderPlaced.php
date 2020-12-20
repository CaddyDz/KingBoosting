<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Coreproc\NovaNotificationFeed\Notifications\NovaBroadcastMessage;

class OrderPlaced extends Notification
{
	use Queueable;

	protected Order $order;

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
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function via($notifiable)
	{
		return ['database', 'broadcast'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable)
	{
		return (new MailMessage)
			->line('The introduction to the notification.')
			->action('Notification Action', url('/'))
			->line('Thank you for using our application!');
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function toArray($notifiable)
	{
		return [
			'level' => 'info',
			'message' => __('A new order has been placed, check it out'),
			'url' => '/dashboard/resources/orders/' . $this->order->id,
			'target' => '_self'
		];
	}

	/**
	 * Get the broadcastable representation of the notification.
	 *
	 * @param  mixed $notifiable
	 * @return BroadcastMessage
	 */
	public function toBroadcast($notifiable)
	{
		return new NovaBroadcastMessage($this->toArray($notifiable));
	}
}
