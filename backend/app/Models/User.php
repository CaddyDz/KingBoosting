<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Role;
use Database\Factories\UserFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Attributes\{Fillable, Hidden};

/**
 * `role` and `visible` aren't fillable: only an admin (role) or the booster themselves (visible) change them.
 *
 * @property Role $role
 */
#[Fillable(['username', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
	/** @use HasFactory<UserFactory> */
	use HasFactory, Notifiable;

	public const VISIBLE_BOOSTERS_CACHE = 'boosters.visible';

	/**
	 * Registration always creates a customer (docs/adr/0002).
	 *
	 * @var array<string, mixed>
	 */
	protected $attributes = [
		'role' => 'customer',
		'visible' => true,
	];

	protected static function booted(): void
	{
		// GET /api/boosters is cached; any change to a booster (or someone becoming or leaving one) refreshes it
		$forget = function (self $user): void {
			if ($user->isBooster() || $user->getOriginal('role') === Role::Booster) {
				cache()->forget(self::VISIBLE_BOOSTERS_CACHE);
			}
		};
		static::saved($forget);
		static::deleted($forget);
	}

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'email_verified_at' => 'datetime',
			'password' => 'hashed',
			'role' => Role::class,
			'visible' => 'boolean',
		];
	}

	public function isAdmin(): bool
	{
		return $this->role === Role::Admin;
	}

	public function isModerator(): bool
	{
		return $this->role === Role::Moderator;
	}

	public function isBooster(): bool
	{
		return $this->role === Role::Booster;
	}

	public function isCustomer(): bool
	{
		return $this->role === Role::Customer;
	}

	public function isStaff(): bool
	{
		return $this->role->isStaff();
	}

	/**
	 * Orders this user placed as a customer.
	 *
	 * @return HasMany<Order, $this>
	 */
	public function orders(): HasMany
	{
		return $this->hasMany(Order::class, 'customer_id');
	}

	/**
	 * Orders this user holds as a booster.
	 *
	 * @return HasMany<Order, $this>
	 */
	public function jobs(): HasMany
	{
		return $this->hasMany(Order::class, 'booster_id');
	}

	/**
	 * @return HasMany<Fine, $this>
	 */
	public function fines(): HasMany
	{
		return $this->hasMany(Fine::class, 'booster_id');
	}

	/**
	 * @return HasMany<Payout, $this>
	 */
	public function payouts(): HasMany
	{
		return $this->hasMany(Payout::class, 'booster_id');
	}
}
