<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pricing;

use Closure;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Pricing\{Catalog, CatalogEntry};

class CatalogController extends Controller
{
	public function index(CatalogEntry $entries): JsonResponse
	{
		return $this->cached('catalog', fn (): array => $entries->index());
	}

	public function show(string $service, Catalog $catalog, CatalogEntry $entries): JsonResponse
	{
		$found = $catalog->find($service);
		abort_if($found === null, 404, 'This service doesn\'t exist.');

		return $this->cached("catalog.{$service}", fn (): array => $entries->describe($found));
	}

	/**
	 * The catalog only changes with config/pricing, so the cache key includes a hash of it:
	 * a deploy that changes prices or options never serves the old catalog.
	 *
	 * @param  Closure(): array<mixed>  $build
	 */
	private function cached(string $key, Closure $build): JsonResponse
	{
		$version = md5((string) json_encode(config('pricing')));

		return response()
			->json(['data' => Cache::rememberForever("{$key}.{$version}", $build)])
			->setPublic()
			->setMaxAge(300);
	}
}
