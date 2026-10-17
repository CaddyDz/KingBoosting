<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\{Request, Response};
use App\Http\Controllers\Controller;
use App\Notifications\AdminNotice;

class NoticeController extends Controller
{
	/**
	 * Send one user a message from support (the legacy Nova NotifyAction).
	 */
	public function store(Request $request, User $user): Response
	{
		$this->authorize('notify', $user);
		$validated = $request->validate(['message' => ['required', 'string', 'max:1000']]);

		$user->notify(new AdminNotice($validated['message']));

		return response()->noContent();
	}
}
