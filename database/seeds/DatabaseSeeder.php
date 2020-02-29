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
        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        // Kinds are needed to seed services
        $this->call(ServiceKindsTableSeeder::class);
        $this->call(ServiceTypesTableSeeder::class);
        $this->call(ServicesTableSeeder::class);
        // Services are needed to seed orders
        $this->call(OrdersTableSeeder::class);
        $this->call(ArticlesTableSeeder::class);
        $this->call(ChampionRolesTableSeeder::class);
        $this->call(ChampionTagsTableSeeder::class);
        $this->call(ChampionsTableSeeder::class);
        $this->call(PrioritiesTableSeeder::class);
        $this->call(FeaturesTableSeeder::class);
        $this->call(TiersTableSeeder::class);
        $this->call(ServersTableSeeder::class);
        $this->call(WinBoostingsTableSeeder::class);
    }
}
