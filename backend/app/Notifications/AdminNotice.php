<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\{BroadcastMessage, MailMessage};

/**
 * A message from an admin to one user, like the legacy Nova "Send custom notification" action.
 */
class AdminNotice extends Notification implements ShouldQueue
{
	use Queueable;

	public function __construct(public string $message) {}

	/**
	 * @return list<string>
	 */
	public function via(User $notifiable): array
	{
		return ['database', 'broadcast', 'mail'];
	}

	public function toMail(User $notifiable): MailMessage
	{
		return (new MailMessage)
			->subject('A message from King Boosting')
			->greeting("Hi {$notifiable->username},")
			->line($this->message)
			->salutation('— King Boosting support');
	}

	/**
	 * @return array{title: string, body: string, order_id: null, url: null}
	 */
	public function toArray(User $notifiable): array
	{
		return ['title' => 'A message from support', 'body' => $this->message, 'order_id' => null, 'url' => null];
	}

	public function toBroadcast(User $notifiable): BroadcastMessage
	{
		return new BroadcastMessage($this->toArray($notifiable));
	}
}
