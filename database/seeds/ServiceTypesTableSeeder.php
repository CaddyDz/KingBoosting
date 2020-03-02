<?php

use App\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'Win Boosting',
            'Division Boosting',
            'Game Boosting',
            'Account Selling',
            'Placement Matches',
            'Champion Boosting',
            'Account Leveling',
            'Clash Boosting',
            'Promotion Boosting',
            'Coaching',
        ];
        foreach ($types as $type) {
            ServiceType::create([
                'type' => $type,
                'icon' => 'icons/' . snake_case($type) . '.png',
            ]);
        }
    }
}
