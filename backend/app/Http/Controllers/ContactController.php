<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContactMessageReceived;

class ContactController extends Controller
{
	/**
	 * A message from the storefront's /contact page: every admin gets it in the app and by email.
	 */
	public function store(ContactRequest $request): JsonResponse
	{
		/** @var array{category: string, name: string, email: string, message: string} $contact */
		$contact = $request->validated();

		Notification::send(User::query()->where('role', Role::Admin)->get(), new ContactMessageReceived($contact));

		return response()->json(['message' => 'Thanks! Your message is with the team, and the reply comes by email.'], 201);
	}
}
