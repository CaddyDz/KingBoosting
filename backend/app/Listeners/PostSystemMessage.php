<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Actions\Chat\PostMessage;
use App\Events\OrderEventRecorded;
use App\Support\OrderEventSummary;

/**
 * Every order action leaves a line in the order's chat, so both sides see what happened and when.
 */
class PostSystemMessage
{
	public function __construct(private PostMessage $postMessage, private OrderEventSummary $summary) {}

	public function handle(OrderEventRecorded $recorded): void
	{
		$line = $this->summary->of($recorded->event);

		if ($line !== null) {
			$this->postMessage->handle(null, $recorded->event->order, $line);
		}
	}
}
