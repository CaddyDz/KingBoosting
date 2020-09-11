<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

class UserTest extends TestCase
{
	public function setUp(): void
	{
		// Create a user globally
		parent::setUp();

		$this->user = create(User::class);
	}

	public function test_user_model_is_authenticatable()
	{
		$this->assertTrue(is_subclass_of(User::class, 'Illuminate\Foundation\Auth\User'));
	}

	public function inpsectHasMany($model, $relationship, $collection)
	{
		$model::unguard(); // Disable mass assignment protection
		$relationship->createMany(create($model, [], 'make', 2)->toArray());
		$this->assertCount(2, $this->user->$collection);
		$this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $relationship);
		$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->user->$collection);
	}
}
