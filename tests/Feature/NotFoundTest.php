<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;

class NotFoundTest extends TestCase
{
	/**
	 * Test that 404 page works
	 *
	 * @return void
	 */
	public function test_not_found(): void
	{
		$response = $this->get('/not-found');
		$response->assertNotFound();
	}
}
