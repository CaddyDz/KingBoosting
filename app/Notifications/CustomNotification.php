<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Coreproc\NovaNotificationFeed\Notifications\NovaBroadcastMessage;
use Illuminate\Notifications\Messages\{BroadcastMessage, MailMessage};

class CustomNotification extends Notification implements ShouldQueue
{
	use Queueable;

	protected string $message;

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
	 * Get the mail representation of the notification.
	 *
	 * @param mixed $notifiable
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable): MailMessage
	{
		return (new MailMessage)
			->line('The introduction to the notification.')
			->action('Notification Action', url('/'))
			->line('Thank you for using our application!');
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param mixed $notifiable
	 * @return array
	 */
	public function toArray($notifiable): array
	{
		return [
			'level' => 'info',
			'message' => $this->message,
			'target' => '_self'
		];
	}

	/**
	 * Get the broadcastable representation of the notification.
	 *
	 * @param  mixed $notifiable
	 * @return BroadcastMessage
	 */
	public function toBroadcast($notifiable): BroadcastMessage
	{
		return new NovaBroadcastMessage($this->toArray($notifiable));
	}
}
