<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'Super Admin']); // All Permissions Granted implicitly
        $member = Role::create(['name' => 'Member']);
        $booster = Role::create(['name' => 'Booster']);
        $moderator = Role::create(['name' => 'Moderator']);
        $accessMembersArea = Permission::create(['name' => 'Access Members Area']);
        // Anyone can place an order without even an account ¯\_(ツ)_/¯
        $member->givePermissionTo($accessMembersArea);
    }
}
