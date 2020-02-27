<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BoostersTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * Assert we can get boosters names from APi
     * This is used to populate the select field on checkout
     *
     * @return void
     */
    public function testGetBoostersNameApiEndpoint()
    {
        $role = Role::create(['name' => 'Booster']);
        $booster = factory(User::class)->create();
        $booster->assignRole($role);
        $response = $this->get('/api/getBoostersNames');
        $response
            ->assertStatus(200)
            ->assertJson([$booster->username]);
    }
}
