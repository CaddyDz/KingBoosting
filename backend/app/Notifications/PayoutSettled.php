<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\{Payout, User};
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\{BroadcastMessage, MailMessage};

/**
 * Sent to a booster when an admin pays them out.
 */
class PayoutSettled extends Notification implements ShouldQueue
{
	use Queueable;

	public function __construct(public Payout $payout) {}

	/**
	 * @return list<string>
	 */
	public function via(User $notifiable): array
	{
		return ['database', 'broadcast', 'mail'];
	}

	public function body(): string
	{
		$amount = number_format($this->payout->amount_cents / 100, 2);
		$orders = $this->payout->orders_count === 1 ? '1 order' : "{$this->payout->orders_count} orders";
		$fines = $this->payout->fines_cents > 0
			? ' after '.number_format($this->payout->fines_cents / 100, 2).' € in fines'
			: '';

		return "You've been paid {$amount} € for {$orders}{$fines}.";
	}

	public function toMail(User $notifiable): MailMessage
	{
		return (new MailMessage)
			->subject('You\'ve been paid')
			->greeting("Hi {$notifiable->username},")
			->line($this->body())
			->action('See your earnings', config('app.frontend_url').'/dashboard/earnings')
			->salutation('— King Boosting');
	}

	/**
	 * @return array{title: string, body: string, order_id: null, url: string}
	 */
	public function toArray(User $notifiable): array
	{
		return [
			'title' => 'Payout sent',
			'body' => $this->body(),
			'order_id' => null,
			'url' => config('app.frontend_url').'/dashboard/earnings',
		];
	}

	public function toBroadcast(User $notifiable): BroadcastMessage
	{
		return new BroadcastMessage($this->toArray($notifiable));
	}
}
