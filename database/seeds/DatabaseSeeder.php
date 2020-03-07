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
        // Services are needed to seed orders
        $this->call(ServicesTableSeeder::class);
        // Tiers are needed to seed orders
        $this->call(TiersTableSeeder::class);
        $this->call(OrdersTableSeeder::class);
        $this->call(ArticlesTableSeeder::class);
        $this->call(ChampionRolesTableSeeder::class);
        $this->call(ChampionTagsTableSeeder::class);
        $this->call(ChampionsTableSeeder::class);
        $this->call(PrioritiesTableSeeder::class);
        $this->call(FeaturesTableSeeder::class);
        $this->call(ServersTableSeeder::class);
    }
}
