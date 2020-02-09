<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'edit orders']);
        Permission::create(['name' => 'delete orders']);
        Permission::create(['name' => 'lock orders']);
        Permission::create(['name' => 'unlock orders']);
        // Every logged in user can access members area so this is redundant for now
        Permission::create(['name' => 'Access Members Area']);

        // create roles and assign created permissions

        // this can be done as separate statements
        $role = Role::create(['name' => 'Member']);
        $role->givePermissionTo('edit orders', 'Access Members Area');

        // or may be done by chaining
        $role = Role::create(['name' => 'Moderator'])
            ->givePermissionTo(['lock orders', 'unlock orders', 'Access Members Area']);

        // or may be done by chaining
        $role = Role::create(['name' => 'Booster'])
            ->givePermissionTo(['lock orders', 'unlock orders', 'Access Members Area']);

        $role = Role::create(['name' => 'Super Admin']);
        // All Permissions Granted implicitly
        $role->givePermissionTo(Permission::all());

        // Anyone can place an order without even an account ¯\_(ツ)_/¯
    }
}
