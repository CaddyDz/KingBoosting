<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Enums\OrderEventType;
use App\Models\{Order, User};

/**
 * The customer sets or changes the Riot login the booster plays on. The event never includes them.
 */
class UpdateOrderCredentials
{
	public function __construct(private OrderTransition $transition) {}

	public function handle(User $customer, Order $order, string $login, string $password): Order
	{
		return $this->transition->run(
			$customer,
			$order,
			'updateCredentials',
			OrderEventType::CredentialsUpdated,
			fn (): array => [
				'account_login' => $login,
				'account_password' => $password,
			],
		);
	}
}
