<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * awaiting_payment → pending → in_progress ⇄ paused → completed → paid_out,
 * plus release from in_progress or paused back to pending.
 */
enum OrderStatus: string
{
	/** Created at checkout; the PaymentIntent hasn't succeeded yet. Only the customer and staff see it. */
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

	/**
	 * The statuses this one may move to.
	 *
	 * @return list<self>
	 */
	public function transitions(): array
	{
		return match ($this) {
			// Paid: straight to in_progress when the customer chose a booster at checkout
			self::AwaitingPayment => [self::Pending, self::InProgress],
			self::Pending => [self::InProgress],
			self::InProgress => [self::Paused, self::Pending, self::Completed],
			self::Paused => [self::InProgress, self::Pending],
			self::Completed => [self::PaidOut],
			self::PaidOut => [],
		};
	}

	public function canTransitionTo(self $status): bool
	{
		return in_array($status, $this->transitions(), true);
	}

	/**
	 * Paid for and not yet finished: the booster is (or will be) working on it.
	 */
	public function isActive(): bool
	{
		return $this === self::Pending || $this === self::InProgress || $this === self::Paused;
	}
}
