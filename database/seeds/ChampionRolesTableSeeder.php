<?php

use App\ChampionRole;
use Illuminate\Database\Seeder;

class ChampionRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            "TOP",
            "DUO_SUPPORT",
            "MIDDLE",
            "JUNGLE",
            "DUO_CARRY",
        ];
        foreach ($roles as $role) {
            ChampionRole::create([
                'name' => $role,
            ]);
        }
    }
}
