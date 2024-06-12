<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class UserTest extends TestCase
{
	/**
	 * A basic feature test example.
	 *
	 * @return void
	 */
	public function test_sanctum_authed_user_can_be_retreived(): void
	{
		Sanctum::actingAs(
			User::factory()->create(),
		);

		$response = $this->get('/api/user');

		$response->assertOk();
	}
}
