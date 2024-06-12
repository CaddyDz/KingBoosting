<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class ServerTest extends TestCase
{
	/**
	 * A basic feature test example.
	 *
	 * @return void
	 */
	public function test_example()
	{
		$this->login();
		$response = $this->get('/');

		$response->assertStatus(200);
	}
}
