<?php

namespace Tests\Feature;

use Tests\TestCase;

class PagesTest extends TestCase
{
	/**
	 * test admin page.
	 *
	 * @return void
	 */
	public function testAdminPage()
	{
		$this->withExceptionHandling();

		$response = $this->get(config('nova.path'));

		$response->assertStatus(302);
	}
}
