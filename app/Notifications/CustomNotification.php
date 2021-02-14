<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mirovit\NovaNotifications\Notification as Notif;

class CustomNotification extends Notification implements ShouldQueue
{
	use Queueable;

	private string $message;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(string $message)
	{
		$this->message = $message;
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
			->info(__('Notice from admin'))
			->subtitle($this->message)
			->toArray();
	}
}
