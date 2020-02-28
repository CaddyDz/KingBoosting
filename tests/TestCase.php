<?php

namespace Tests;

use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;

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
    protected function login(string $role, ?User $user = null): TestCase
    {
        $role = Role::create(['name' => $role]);
        $role->givePermissionTo(Permission::all());
        $user = $user ?: create(User::class);
        $user->assignRole($role);
        $this->be($user);
        return $this;
    }
}
