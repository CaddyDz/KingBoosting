<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\{Order, User};
use Illuminate\Support\Facades\Gate;
use App\Enums\{OrderEventType, OrderStatus};
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * An order as the signed-in user may see it. Every field that depends on the role asks OrderPolicy,
 * and `allowed_actions` tells the UI which buttons to show, so it never re-implements the rules.
 *
 * @mixin Order
 */
class OrderResource extends JsonResource
{
	/**
	 * Policy ability => the action name the API and UI use.
	 */
	private const ACTIONS = [
		'claim' => 'claim',
		'assign' => 'assign',
		'pause' => 'pause',
		'resume' => 'resume',
		'release' => 'release',
		'complete' => 'complete',
		'markPaidOut' => 'mark-paid-out',
		'updateCredentials' => 'update-credentials',
	];

	private bool $withDetails = false;

	/**
	 * Include the credentials (when allowed) and the timeline: the order page, not the lists.
	 */
	public function withDetails(): static
	{
		$this->withDetails = true;

		return $this;
	}

	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		/** @var User $user */
		$user = $request->user();
		$gate = Gate::forUser($user);
		$canViewPrice = $gate->allows('viewPrice', $this->resource);

		return [
			'id' => $this->id,
			'title' => $this->title,
			'game' => ['value' => $this->game->value, 'label' => $this->game->label()],
			'service' => $this->service,
			'server' => $this->server,
			// Paying the booster is between them and King Boosting: to the customer, the order is simply completed
			'status' => ['value' => $this->status->value, 'label' => $user->isCustomer() && $this->status === OrderStatus::PaidOut ? OrderStatus::Completed->label() : $this->status->label()],
			'account_name' => $this->account_name,
			'options' => array_values(array_map(
				fn (array $option): string => (string) $option['label'],
				$this->quote['options'] ?? [],
			)),
			'comment' => $this->comment,
			'customer' => $this->when(
				$user->isStaff() || $gate->allows('chat', $this->resource) || $this->customer_id === $user->id,
				fn (): array => ['id' => $this->customer_id, 'username' => $this->customer?->username],
			),
			'booster' => $this->booster_id === null ? null : ['id' => $this->booster_id, 'username' => $this->booster?->username],
			'price_cents' => $this->when($canViewPrice, $this->price_cents),
			'booster_share_pct' => $this->when($user->isAdmin(), $this->booster_share_pct),
			'booster_share_cents' => $this->when($gate->allows('viewBoosterShare', $this->resource), fn (): int => $this->boosterShareCents()),
			'quote' => $this->when($this->withDetails && $canViewPrice, fn (): array => [
				'lines' => $this->quote['lines'] ?? [],
				'options' => $this->quote['options'] ?? [],
				'total_cents' => $this->quote['total_cents'] ?? $this->price_cents,
			]),
			'credentials' => $this->when(
				$this->withDetails && $gate->allows('viewCredentials', $this->resource),
				fn (): array => ['login' => $this->account_login, 'password' => $this->account_password],
			),
			'events' => $this->when($this->withDetails, fn () => OrderEventResource::collection($this->events()
				->with('actor')
				->when($user->isCustomer(), fn ($query) => $query->where('type', '!=', OrderEventType::PaidOut))
				->oldest('id')
				->get())),
			'allowed_actions' => array_values(array_filter(
				self::ACTIONS,
				fn (string $action, string $ability): bool => $gate->allows($ability, $this->resource),
				ARRAY_FILTER_USE_BOTH,
			)),
			'can_chat' => $gate->allows('chat', $this->resource),
			'created_at' => $this->created_at,
			'paid_at' => $this->paid_at,
			'claimed_at' => $this->claimed_at,
			'completed_at' => $this->completed_at,
			'paid_out_at' => $this->when(! $user->isCustomer(), $this->paid_out_at),
		];
	}
}
