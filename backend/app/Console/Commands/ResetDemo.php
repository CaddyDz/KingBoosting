<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Database\Seeders\DemoSeeder;
use Illuminate\Console\{Command, ConfirmableTrait};
use Illuminate\Console\Attributes\{Description, Signature};

#[Signature('demo:reset {--force : Run in production without asking}')]
#[Description('Wipe the database and seed the demo accounts, orders, chats and payouts again')]
class ResetDemo extends Command
{
	use ConfirmableTrait;

	public function handle(): int
	{
		if (! $this->confirmToProceed()) {
			return self::FAILURE;
		}

		$this->call('migrate:fresh', ['--force' => true, '--seed' => true, '--seeder' => DemoSeeder::class]);

		// The wipe dropped the cache table the queue worker polls; restart it cleanly. Reverb keeps no database
		// state: if it fell over while the table was missing, its restart policy has already brought it back, and
		// an explicit reverb:restart would only drop every connection again ten seconds later.
		$this->callSilently('queue:restart');

		$this->components->info('The demo has been reset. Every account uses DEMO_PASSWORD.');

		return self::SUCCESS;
	}
}
