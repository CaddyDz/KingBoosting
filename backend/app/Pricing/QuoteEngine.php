<?php

declare(strict_types=1);

namespace App\Pricing;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator as ValidatorFactory;
use App\Pricing\Strategies\{DivisionLadder, Mastery, PerGame, PerWin, PricingStrategy, Unrated};

/**
 * Prices an order on the server (docs/adr/0003). Every amount is in euro cents; the multiplier and the
 * option percentages are applied as exact fractions and the total is rounded once, half up, which
 * reproduces the legacy storefront's prices (docs/pricing-golden.json).
 */
class QuoteEngine
{
	private const STRATEGIES = [
		'division-ladder' => DivisionLadder::class,
		'per-game' => PerGame::class,
		'per-win' => PerWin::class,
		'mastery' => Mastery::class,
		'unrated' => Unrated::class,
	];

	public function __construct(private Catalog $catalog) {}

	/**
	 * @param  array<string, mixed>  $input  the service's inputs plus `options`, a list of option keys
	 *
	 * @throws ValidationException
	 */
	public function quote(string $serviceKey, array $input): Quote
	{
		$service = $this->catalog->find($serviceKey)
			?? throw ValidationException::withMessages(['service' => 'This service doesn\'t exist.']);
		$strategy = $this->strategy($service);
		$input = $this->validate($service, $strategy, $input);
		$priced = $strategy->price($service, $input);

		$options = array_values(array_filter(
			$service->options,
			fn (array $option): bool => in_array($option['key'], $input['options'] ?? [], true),
		));

		return new Quote(
			service: $service,
			title: $priced->title,
			inputs: [...$priced->inputs, 'options' => array_column($options, 'key')],
			lines: $this->lines($service, $priced, $options),
			options: array_map(fn (array $option): array => [
				'key' => $option['key'],
				'label' => $option['label'],
				'percent' => $option['percent'],
			], $options),
			totalCents: $this->total($service, $priced, $options),
		);
	}

	/**
	 * subtotal × multiplier × (100% + options), rounded half up once.
	 *
	 * @param  list<array{percent: int}>  $options
	 */
	private function total(Service $service, Priced $priced, array $options): int
	{
		$percent = 100 + array_sum(array_column($options, 'percent'));

		return self::roundHalfUp($priced->subtotalCents() * $service->multiplierPct * $percent, 100 * 100);
	}

	/**
	 * The strategy's lines, then one line for the multiplier and one per paid option. Each line is the
	 * difference between two rounded running totals, so the lines always add up to the total.
	 *
	 * @param  list<array{label: string, percent: int}>  $options
	 * @return list<array{label: string, cents: int}>
	 */
	private function lines(Service $service, Priced $priced, array $options): array
	{
		$lines = $priced->lines;
		$subtotal = $priced->subtotalCents();
		$running = $subtotal;

		if ($service->multiplierPct !== 100) {
			$multiplied = self::roundHalfUp($subtotal * $service->multiplierPct, 100);
			$lines[] = ['label' => $service->label.' (×'.($service->multiplierPct / 100).')', 'cents' => $multiplied - $running];
			$running = $multiplied;
		}

		$percent = 100;
		foreach ($options as $option) {
			if ($option['percent'] === 0) {
				continue;
			}
			$percent += $option['percent'];
			$next = self::roundHalfUp($subtotal * $service->multiplierPct * $percent, 100 * 100);
			$sign = $option['percent'] > 0 ? '+' : '';
			$lines[] = ['label' => "{$option['label']} ({$sign}{$option['percent']}%)", 'cents' => $next - $running];
			$running = $next;
		}

		return $lines;
	}

	/**
	 * @param  array<string, mixed>  $input
	 * @return array<string, mixed>
	 *
	 * @throws ValidationException
	 */
	private function validate(Service $service, PricingStrategy $strategy, array $input): array
	{
		$validator = ValidatorFactory::make($input, [
			...$strategy->rules($service, $input),
			'options' => ['sometimes', 'array'],
			'options.*' => ['string', 'distinct', Rule::in($service->optionKeys())],
		]);

		$validator->after(function (Validator $validator) use ($service, $strategy, $input): void {
			if ($validator->errors()->isEmpty()) {
				foreach ($strategy->errors($service, $input) as $field => $message) {
					$validator->errors()->add($field, $message);
				}
			}
		});

		return $validator->validate();
	}

	private function strategy(Service $service): PricingStrategy
	{
		return app(self::STRATEGIES[$service->strategy]);
	}

	/**
	 * $numerator / $denominator rounded to the nearest integer, halves up. Prices are never negative.
	 */
	public static function roundHalfUp(int $numerator, int $denominator): int
	{
		return intdiv(2 * $numerator + $denominator, 2 * $denominator);
	}
}
