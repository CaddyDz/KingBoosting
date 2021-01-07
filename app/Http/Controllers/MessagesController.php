<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\SendMessage;
use Illuminate\Http\Request;
use App\Models\{Message, User};

class MessagesController extends Controller
{
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$message = Message::create([
			'sender_id' => auth()->id(),
			'recepient_id' => $request->to,
			'content' => $request->message,
			'chat_id' => $request->chat
		]);

		broadcast(new SendMessage($message));

		return response([
			'message' => [
				'content' => $message->content,
				'sender' => User::find($message->sender_id)->username,
				'at' => $message->created_at->format('H:i'),
			]
		]);
	}
}
