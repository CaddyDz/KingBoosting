<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactMail extends Mailable implements ShouldQueue
{
	use Queueable, SerializesModels;

	// Array of email data from request object
	public array $details;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct(array $details)
	{
		$this->details = $details;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		$this->from([
			'address' => $this->details['email'],
			'name' => $this->details['name'],
		])->subject($this->details['subject'])
			->markdown('emails.contact')
			->with('details', $this->details);
	}
}
