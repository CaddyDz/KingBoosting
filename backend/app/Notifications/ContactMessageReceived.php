<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\{BroadcastMessage, MailMessage};

/**
 * A message from the storefront's contact form, for every admin. Replying to the email answers the sender.
 */
class ContactMessageReceived extends Notification implements ShouldQueue
{
	use Queueable;

	private const CATEGORIES = [
		'question' => 'General question',
		'order' => 'Order issue',
		'bug' => 'Bug report',
		'other' => 'Other',
	];

	/**
	 * @param  array{category: string, name: string, email: string, message: string}  $contact
	 */
	public function __construct(public array $contact) {}

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
			->subject("Contact form: {$this->category()}")
			->replyTo($this->contact['email'], $this->contact['name'])
			->greeting("{$this->contact['name']} wrote:")
			->line($this->contact['message'])
			->salutation("Reply to this email to answer {$this->contact['email']}.");
	}

	/**
	 * @return array{title: string, body: string, order_id: null, url: null}
	 */
	public function toArray(User $notifiable): array
	{
		return [
			'title' => "Contact form: {$this->category()}",
			'body' => "{$this->contact['name']} ({$this->contact['email']}): ".Str::limit($this->contact['message'], 140),
			'order_id' => null,
			'url' => null,
		];
	}

	public function toBroadcast(User $notifiable): BroadcastMessage
	{
		return new BroadcastMessage($this->toArray($notifiable));
	}

	private function category(): string
	{
		return self::CATEGORIES[$this->contact['category']] ?? 'Other';
	}
}
