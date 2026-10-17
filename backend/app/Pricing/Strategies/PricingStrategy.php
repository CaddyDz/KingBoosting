<?php

declare(strict_types=1);

namespace App\Pricing\Strategies;

use App\Pricing\{Priced, Service};

/**
 * How one kind of service is priced: its inputs, how to check them, and the price before options.
 */
interface PricingStrategy
{
	/**
	 * Laravel validation rules for the strategy's inputs.
	 *
	 * @param  array<string, mixed>  $input
	 * @return array<string, mixed>
	 */
	public function rules(Service $service, array $input): array;

	/**
	 * Checks that need the whole input once the rules pass, e.g. that the desired rank is above the current one.
	 *
	 * @param  array<string, mixed>  $input
	 * @return array<string, string> field => error message
	 */
	public function errors(Service $service, array $input): array;

	/**
	 * @param  array<string, mixed>  $input  validated input
	 */
	public function price(Service $service, array $input): Priced;
}
