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
	public function run(): void
	{
		$order = [
			'purchase' => 'Silver I to Gold IV',
			'service' => 'Division Boosting',
			'server' => 'EU-West',
			'options' =>
			[
				'Specific champions',
				'Priority order (2x speed) at',
				'With Streaming at',
				'Solo/Duo',
				'offline',
			],
			'price' => 30,
			'summoner' => 'caddydz',
			'booster_id' => User::role('Booster')->first()->id,
			'client_id' => User::role('Member')->first()->id,
			'comment' => NULL,
		];
		Order::create($order);
	}
}
