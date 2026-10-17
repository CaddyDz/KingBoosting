<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\{Order, User};
use App\Enums\{OrderStatus, Role};
use Illuminate\Auth\Access\Response;

/**
 * The only authorisation layer for orders (docs/adr/0002). docs/order-rules.md has the full table.
 *
 * Transitions answer in two steps: 403 when the user may never do this to the order,
 * 409 when they could but the order's status doesn't allow it (e.g. it was already claimed).
 */
class OrderPolicy
{
	public function viewAny(User $user): bool
	{
		return true;
	}

	/**
	 * The jobs board of pending orders.
	 */
	public function viewJobs(User $user): bool
	{
		return $user->isBooster() || $user->isStaff();
	}

	public function view(User $user, Order $order): bool
	{
		return match ($user->role) {
			Role::Admin, Role::Moderator => true,
			Role::Booster => $order->status === OrderStatus::Pending || $this->holds($user, $order),
			Role::Customer => $this->owns($user, $order),
		};
	}

	/**
	 * The Riot login and password. Moderators never see them; boosters only while they're playing.
	 */
	public function viewCredentials(User $user, Order $order): bool
	{
		return match ($user->role) {
			Role::Admin => true,
			Role::Moderator => false,
			Role::Booster => $this->holds($user, $order)
				&& in_array($order->status, [OrderStatus::InProgress, OrderStatus::Paused], true),
			Role::Customer => $this->owns($user, $order),
		};
	}

	/**
	 * What the customer paid.
	 */
	public function viewPrice(User $user, Order $order): bool
	{
		return $user->isAdmin() || $this->owns($user, $order);
	}

	/**
	 * What the booster earns: shown on the jobs board and to the booster who holds the order.
	 */
	public function viewBoosterShare(User $user, Order $order): bool
	{
		return $user->isAdmin()
			|| ($user->isBooster() && ($order->status === OrderStatus::Pending || $this->holds($user, $order)));
	}

	public function claim(User $user, Order $order): Response
	{
		return $this->decide(
			$user->isBooster(),
			$order->status === OrderStatus::Pending && $order->booster_id === null,
			'This order has already been claimed.',
		);
	}

	public function assign(User $user, Order $order): Response
	{
		return $this->decide(
			$user->isStaff(),
			$order->status === OrderStatus::Pending,
			'Only orders waiting for a booster can be assigned.',
		);
	}

	public function pause(User $user, Order $order): Response
	{
		return $this->decide(
			$user->isStaff() || $this->isAssignedBooster($user, $order) || $this->owns($user, $order),
			$order->status === OrderStatus::InProgress,
			'Only an order in progress can be paused.',
		);
	}

	public function resume(User $user, Order $order): Response
	{
		return $this->decide(
			$user->isStaff() || $this->isAssignedBooster($user, $order) || $this->owns($user, $order),
			$order->status === OrderStatus::Paused,
			'Only a paused order can be resumed.',
		);
	}

	/**
	 * Hand the order back to the jobs board.
	 */
	public function release(User $user, Order $order): Response
	{
		return $this->decide(
			$user->isStaff() || $this->isAssignedBooster($user, $order),
			in_array($order->status, [OrderStatus::InProgress, OrderStatus::Paused], true),
			'Only an order in progress or paused can be released.',
		);
	}

	/**
	 * Only the booster playing the order can say it's done.
	 */
	public function complete(User $user, Order $order): Response
	{
		return $this->decide(
			$this->isAssignedBooster($user, $order),
			$order->status === OrderStatus::InProgress,
			'Only an order in progress can be completed.',
		);
	}

	public function markPaidOut(User $user, Order $order): Response
	{
		return $this->decide(
			$user->isAdmin(),
			$order->status === OrderStatus::Completed,
			'Only completed orders can be paid out.',
		);
	}

	/**
	 * The customer can change their Riot credentials until the order is done.
	 */
	public function updateCredentials(User $user, Order $order): Response
	{
		return $this->decide(
			$this->owns($user, $order),
			$order->status === OrderStatus::AwaitingPayment || $order->status->isActive(),
			'The credentials of a finished order can no longer be changed.',
		);
	}

	/**
	 * Read and post in the order's chat. Staff post as "Support".
	 */
	public function chat(User $user, Order $order): bool
	{
		return match ($user->role) {
			Role::Admin, Role::Moderator => $order->status !== OrderStatus::AwaitingPayment,
			Role::Booster => $this->holds($user, $order),
			Role::Customer => $this->owns($user, $order) && $order->status !== OrderStatus::AwaitingPayment,
		};
	}

	private function owns(User $user, Order $order): bool
	{
		return $user->isCustomer() && $order->customer_id === $user->id;
	}

	/**
	 * The booster set on the order, whatever its status. A booster picked at checkout is set before payment.
	 */
	private function isAssignedBooster(User $user, Order $order): bool
	{
		return $user->isBooster() && $order->booster_id === $user->id;
	}

	/**
	 * The booster has claimed (or been given) the paid order and is working on it, or did.
	 */
	private function holds(User $user, Order $order): bool
	{
		return $this->isAssignedBooster($user, $order)
			&& ! in_array($order->status, [OrderStatus::AwaitingPayment, OrderStatus::Pending], true);
	}

	private function decide(bool $mayEverDoThis, bool $statusAllowsIt, string $staleMessage): Response
	{
		if (! $mayEverDoThis) {
			return Response::deny();
		}

		return $statusAllowsIt ? Response::allow() : Response::denyWithStatus(409, $staleMessage);
	}
}
