<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * What happened to an order. Each row in order_events drives the order timeline and a system chat message.
 */
enum OrderEventType: string
{
	case Created = 'created';
	case PaymentSucceeded = 'payment_succeeded';
	case PaymentFailed = 'payment_failed';
	case Claimed = 'claimed';
	case Assigned = 'assigned';
	case Paused = 'paused';
	case Resumed = 'resumed';
	case Released = 'released';
	case Completed = 'completed';
	case PaidOut = 'paid_out';
	case CredentialsUpdated = 'credentials_updated';
}
