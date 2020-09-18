<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFoundTest extends TestCase
{
	/**
	 * Test that 404 page works
	 *
	 * @return void
	 */
	public function test_not_found(): void
	{
		$this->expectException(NotFoundHttpException::class);
		$this->get('/not-found');
	}
}
