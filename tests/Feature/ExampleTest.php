<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testBasicTest(): void
	{
		$response = $this->get('/api/user');

		$response->assertStatus(200);
	}
}
