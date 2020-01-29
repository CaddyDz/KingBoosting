<?php

use App\GameBoosting;
use Illuminate\Database\Seeder;

class GameBoostingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GameBoosting::create(['games_available' => 10]);
    }
}
