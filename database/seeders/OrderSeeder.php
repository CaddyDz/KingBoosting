<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\{Order, User};
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$order = [
			'purchase' => 'Silver I to Gold IV',
			'service' => 'Division Boosting',
			'server' => 'EU-West',
			'options' =>
			[
				0 => 'Specific champions',
				1 => 'Priority order (2x speed) at',
				2 => 'With Streaming at',
				3 => 'Solo/Duo',
				4 => 'offline',
			],
			'price' => 32.967,
			'summoner' => 'caddydz',
			'booster_id' => User::role('Booster')->first()->id,
			'client_id' => User::role('Member')->first()->id,
			'comment' => NULL,
		];
		Order::create($order);
	}
}
