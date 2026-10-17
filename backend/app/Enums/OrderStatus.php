<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: string
{
	/** Created at checkout; the PaymentIntent hasn't succeeded yet. Only the customer sees it. */
	case AwaitingPayment = 'awaiting_payment';
	/** Paid and waiting for a booster to claim it. */
	case Pending = 'pending';
	case InProgress = 'in_progress';
	case Paused = 'paused';
	case Completed = 'completed';
	/** The booster's share has been paid out. */
	case PaidOut = 'paid_out';

	public function label(): string
	{
		return match ($this) {
			self::AwaitingPayment => 'Awaiting payment',
			self::Pending => 'Waiting for a booster',
			self::InProgress => 'In progress',
			self::Paused => 'Paused',
			self::Completed => 'Completed',
			self::PaidOut => 'Paid out',
		};
	}
}
