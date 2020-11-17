<?php

use App\Discount;
use Illuminate\Database\Seeder;

class DiscountsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		factory(Discount::class, 50)->create();
	}
}
