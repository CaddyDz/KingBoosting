<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Auth\AuthenticationException;

class RoutesTest extends TestCase
{
	/**
	 * Test main route.
	 *
	 * @return void
	 */
	public function test_main_route()
	{
		$this->withExceptionHandling();
		$response = $this->get('/api');

		$response->assertNotFound();
	}

	/**
	 * Test MethodNotAllowedException handler
	 *
	 * assert that a MethodNotAllowedException is caught
	 *
	 * @return void
	 */
	public function test_method_not_allowed_exception_handler()
	{
		$this->withExceptionHandling();
		$response = $this->post('/api/servers');
		$response->assertStatus(400);
		$response->assertJsonStructure([
			'status'
		]);
		$response->assertExactJson([
			'status' => 'Bad Request'
		]);
	}

	/**
	 * Test MethodNotAllowedException handler
	 *
	 * assert that a MethodNotAllowedException is caught
	 *
	 * @return void
	 */
	public function test_exception_rendering()
	{
		$this->expectException(AuthenticationException::class);
		$this->get('oauth/authorize');
	}
}
