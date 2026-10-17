<?php

declare(strict_types=1);

namespace App\Pricing;

use App\Enums\Game;

/**
 * The services the storefront sells (config/pricing/services.php).
 */
class Catalog
{
	/**
	 * @return array<string, Service>
	 */
	public function services(): array
	{
		$services = [];
		foreach (config('pricing.services') as $key => $config) {
			$services[$key] = Service::fromConfig($key, $config);
		}

		return $services;
	}

	/**
	 * @return list<string>
	 */
	public function keys(): array
	{
		return array_keys(config('pricing.services'));
	}

	public function find(string $key): ?Service
	{
		$config = config("pricing.services.{$key}");

		return is_array($config) ? Service::fromConfig($key, $config) : null;
	}

	/**
	 * @return array<string, string> server key => label
	 */
	public function servers(Game $game): array
	{
		return config("pricing.servers.{$game->value}");
	}
}
