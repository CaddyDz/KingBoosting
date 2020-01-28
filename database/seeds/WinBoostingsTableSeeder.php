<?php

use App\WinBoosting;
use Illuminate\Database\Seeder;

class WinBoostingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WinBoosting::create(['wins_available' => 10]);
        WinBoosting::create(['wins_available' => 7]);
    }
}
