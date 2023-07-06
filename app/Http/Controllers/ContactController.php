<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Exception;
use App\Mail\ContactMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
	/**
	 * Send a contact message.
	 *
	 * @param App\Http\Requests\ContactRequest $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function send(ContactRequest $request): JsonResponse
	{
		try {
			Mail::to(config('mail.address'))->send(new ContactMail($request->validated()));
		} catch (Exception $exception) {
			logger()->error($exception->getMessage());
			report($exception);
			throw $exception;
		}
		return response()->json([
			'message' => __('You message has been sent'),
		], 201);
	}
}
