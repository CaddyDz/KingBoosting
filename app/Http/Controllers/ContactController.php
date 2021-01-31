<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Response;
use Swift_TransportException;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
	/**
	 * Send a contact message.
	 *
	 * @param App\Http\Requests\ContactRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function send(ContactRequest $request): Response
	{
		try {
			Mail::to(config('mail.address'))->send(new ContactMail($this->trimRequest($request->all())));
		} catch (Swift_TransportException $exception) {
			logger()->error($exception->getMessage());
			return response([
				'message' => __('No internet connection detected'),
			], 599);
		}
		return response([
			'message' => __('You message has been sent'),
		]);
	}

	/**
	 * Remove any additional data on the request
	 * Merely a security measure.
	 *
	 * @return array $details
	 */
	public function trimRequest(array $data): array
	{
		$details = [
			'name' => $data['name'],
			'email' => $data['email'],
			'subject' => $data['subject'],
			'message' => $data['message'],
		];

		return $details;
	}
}
