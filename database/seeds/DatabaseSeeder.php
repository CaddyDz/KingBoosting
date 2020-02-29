<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ArticlesTableSeeder::class);
        $this->call(OrdersTableSeeder::class);
        $this->call(ChampionRolesTableSeeder::class);
        $this->call(ChampionTagsTableSeeder::class);
        $this->call(ChampionsTableSeeder::class);
        $this->call(PrioritiesTableSeeder::class);
        $this->call(FeaturesTableSeeder::class);
        $this->call(TiersTableSeeder::class);
        $this->call(ServiceKindsTableSeeder::class);
        $this->call(ServiceTypesTableSeeder::class);
        $this->call(ServersTableSeeder::class);
        $this->call(WinBoostingsTableSeeder::class);
        $this->call(RolesAndPermissionsTablesSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ServicesTableSeeder::class);
    }
}
