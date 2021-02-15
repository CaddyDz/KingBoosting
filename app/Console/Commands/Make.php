<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Make extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'make:entity {model}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Scaffold an entity';

	private string $model;

	/**
	 * The entity classes types to be created.
	 *
	 * @var array $classes
	 */
	private array $classes = [
		'factory',
		'seeder',
		'resource',
		'test',
	];

	/**
	 * The request classes to be created.
	 *
	 * @var array $requests
	 */
	private array $requests = [
		'Create',
		'Update',
		'Delete',
	];

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
	public function handle(): int
	{
		$this->model = $this->argument('model');
		Artisan::call('make:model ' . $this->model . ' -mcr');
		$this->createClasses($this->classes);
		$this->createRequests($this->requests);
		Artisan::call('nova:resource ' . $this->model);
		Artisan::call('make:policy ' . $this->model.'Policy --model=' . $this->model);
		Artisan::call('make:observer ' . $this->model.'Observer --model=' . $this->model);
		return 0;
	}

	private function createClasses(array $classes): void
	{
		foreach ($classes as $class) {
			Artisan::call("make:$class " . $this->model . title_case($class));
		}
	}

	private function createRequests(array $requests): void
	{
		foreach ($requests as $request) {
			Artisan::call('make:request ' . $request.$this->model.'Request');
		}
	}
}
