<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Role;
use Database\Factories\UserFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Attributes\{Fillable, Hidden};

/**
 * `role` and `visible` aren't fillable: only an admin (role) or the booster themselves (visible) change them.
 */
#[Fillable(['username', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
	/** @use HasFactory<UserFactory> */
	use HasFactory, Notifiable;

	/**
	 * Registration always creates a customer (docs/adr/0002).
	 *
	 * @var array<string, mixed>
	 */
	protected $attributes = [
		'role' => 'customer',
		'visible' => true,
	];

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
}
