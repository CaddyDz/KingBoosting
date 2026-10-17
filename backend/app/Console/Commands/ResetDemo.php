<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\DemoSeeder;
use Illuminate\Console\ConfirmableTrait;
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

		$this->components->info('The demo has been reset. Every account uses DEMO_PASSWORD.');

		return self::SUCCESS;
	}
}
