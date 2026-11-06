<?php

declare(strict_types=1);

namespace Tests\Fakes;

use LogicException;
use Stripe\HttpClient\ClientInterface;

/**
 * Answers Stripe API requests from a script, and records them, so the gateway runs its real code without the network.
 */
class ScriptedStripeApi implements ClientInterface
{
	/** @var list<array{method: string, path: string, params: array<string, mixed>, idempotency_key: string|null}> */
	public array $requests = [];

	/** @param  list<array{0: int, 1: array<string, mixed>}>  $responses  status code and JSON body, in order */
	public function __construct(private array $responses) {}

	public function request($method, $absUrl, $headers, $params, $hasFile, $apiMode = 'v1', $maxNetworkRetries = null): array
	{
		$key = collect($headers)->first(fn (string $header): bool => str_starts_with($header, 'Idempotency-Key: '));
		$this->requests[] = [
			'method' => $method,
			'path' => (string) parse_url($absUrl, PHP_URL_PATH),
			'params' => $params,
			'idempotency_key' => $key === null ? null : substr($key, strlen('Idempotency-Key: ')),
		];
		[$status, $body] = array_shift($this->responses) ?? throw new LogicException("Unexpected Stripe request: {$method} {$absUrl}");

		return [(string) json_encode($body), $status, []];
	}
}
