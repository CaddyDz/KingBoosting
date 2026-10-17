<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\{Request, Response};
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NotificationController extends Controller
{
	/**
	 * The bell: the signed-in user's notifications, newest first, with the unread count.
	 */
	public function index(Request $request): AnonymousResourceCollection
	{
		$user = $this->user($request);

		return NotificationResource::collection($user->notifications()->paginate(20))
			->additional(['meta' => ['unread_count' => $user->unreadNotifications()->count()]]);
	}

	public function read(Request $request, string $notification): Response
	{
		$this->user($request)->notifications()->whereKey($notification)->firstOrFail()->markAsRead();

		return response()->noContent();
	}

	public function readAll(Request $request): Response
	{
		$this->user($request)->unreadNotifications()->update(['read_at' => now()]);

		return response()->noContent();
	}

	private function user(Request $request): User
	{
		/** @var User */
		return $request->user();
	}
}
