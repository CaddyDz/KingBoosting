<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\{DatabaseMigrations, TestCase as BaseTestCase};

abstract class TestCase extends BaseTestCase
{
	use CreatesApplication, DatabaseMigrations;

	public function setUp(): void
	{
		parent::setUp();
		$this->withoutExceptionHandling();
	}
}
