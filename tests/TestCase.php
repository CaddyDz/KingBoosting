<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\{DatabaseMigrations, TestCase as BaseTestCase};

abstract class TestCase extends BaseTestCase
{
	use CreatesApplication, DatabaseMigrations;

	public function setUp(): void
	{
		parent::setUp();
		$this->withoutExceptionHandling();
	}

	/**
	 * Set the currently logged in user for the application.
	 *
	 * @param Illuminate\Contracts\Auth\Authenticatable
	 *
	 * @return App\User authenticated
	 */
	protected function login($user = null): TestCase
	{
		$user = $user ?: create(User::class);
		$this->be($user);

		return $this;
	}
}
