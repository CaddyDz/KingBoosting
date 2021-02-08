<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class Clean extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'clean';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Delete temporary files';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		File::delete('.php_cs.cache');
		File::delete('.phpstorm.meta.php');
		File::delete('.phpunit.result.cache');
		File::delete('nova.zip');
		File::delete('_ide_helper.php');
		return 0;
	}
}
