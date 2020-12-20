<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Registered extends Mailable
{
	use Queueable, SerializesModels;

	public User $user;
	public string $password;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, string $password)
	{
		$this->user = $user;
		$this->password = $password;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->markdown('emails.auth.registered');
	}
}
