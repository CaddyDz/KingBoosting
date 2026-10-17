<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\{Order, User};
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\{BroadcastMessage, MailMessage};

/**
 * A notification about one order: stored for the bell, pushed live, and emailed.
 * Subclasses say what happened; this class decides how it's delivered.
 */
abstract class OrderNotification extends Notification implements ShouldQueue
{
	use Queueable;

	public function __construct(public Order $order) {}

	abstract public function title(): string;

	abstract public function body(User $notifiable): string;

	/**
	 * Get the notification's delivery channels.
	 *
	 * @return list<string>
	 */
	public function via(User $notifiable): array
	{
		return ['database', 'broadcast', 'mail'];
	}

	public function toMail(User $notifiable): MailMessage
	{
		return (new MailMessage)
			->subject($this->title())
			->greeting("Hi {$notifiable->username},")
			->line($this->body($notifiable))
			->action('Open the order', $this->url())
			->salutation('— King Boosting');
	}

	/**
	 * What the bell lists, and what the broadcast carries.
	 *
	 * @return array{title: string, body: string, order_id: int, url: string}
	 */
	public function toArray(User $notifiable): array
	{
		return [
			'title' => $this->title(),
			'body' => $this->body($notifiable),
			'order_id' => $this->order->id,
			'url' => $this->url(),
		];
	}

	public function toBroadcast(User $notifiable): BroadcastMessage
	{
		return new BroadcastMessage($this->toArray($notifiable));
	}

	protected function url(): string
	{
		return config('app.frontend_url')."/dashboard/orders/{$this->order->id}";
	}
}
