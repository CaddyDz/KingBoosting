<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\OrderEvent;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;

/**
 * Something happened to an order. Fired once the action's transaction has committed,
 * so listeners (system chat messages, notifications) never see a rolled-back change.
 */
class OrderEventRecorded implements ShouldDispatchAfterCommit
{
	use Dispatchable, SerializesModels;

	public function __construct(public OrderEvent $event) {}
}
