<?php

declare(strict_types=1);

namespace App\Http\Controllers\Chat;

use App\Events\MessagesRead;
use App\Models\{Order, User};
use App\Actions\Chat\PostMessage;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use Illuminate\Http\{JsonResponse, Request, Response};

class MessageController extends Controller
{
	private const PAGE = 30;

	/**
	 * The chat history, newest page first: `?before=<id>` loads the page before that message.
	 * Messages in a page are oldest first, ready to prepend.
	 */
	public function index(Request $request, Order $order): JsonResponse
	{
		$this->authorize('chat', $order);
		$validated = $request->validate(['before' => ['sometimes', 'integer', 'min:1']]);

		$page = $order->messages()
			->with('sender')
			->when($validated['before'] ?? null, fn ($query, int $before) => $query->where('id', '<', $before))
			->orderByDesc('id')
			->limit(self::PAGE + 1)
			->get();

		$hasMore = $page->count() > self::PAGE;
		$messages = $page->take(self::PAGE)->reverse()->values();

		return response()->json([
			'data' => MessageResource::collection($messages),
			'meta' => ['has_more' => $hasMore, 'before' => $hasMore ? $messages->first()?->id : null],
		]);
	}

	public function store(Request $request, Order $order, PostMessage $postMessage): JsonResponse
	{
		$this->authorize('chat', $order);
		$validated = $request->validate(['body' => ['required', 'string', 'max:2000']]);

		/** @var User $sender */
		$sender = $request->user();
		$message = $postMessage->handle($sender, $order, trim($validated['body']));

		return (new MessageResource($message->load('sender')))->response()->setStatusCode(201);
	}

	/**
	 * The customer or the booster has read everything the others sent. Staff reading doesn't count.
	 */
	public function read(Request $request, Order $order): Response
	{
		$this->authorize('chat', $order);

		/** @var User $reader */
		$reader = $request->user();
		$unread = $order->messages()
			->whereNull('read_at')
			->where(fn ($query) => $query->whereNull('sender_id')->orWhere('sender_id', '!=', $reader->id));
		$lastId = (int) $unread->max('id');

		if (! $reader->isStaff() && $lastId > 0) {
			$unread->update(['read_at' => now()]);
			broadcast(new MessagesRead($order->id, $reader->id, $lastId))->toOthers();
		}

		return response()->noContent();
	}
}
