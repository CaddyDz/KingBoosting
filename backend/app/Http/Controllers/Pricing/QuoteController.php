<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pricing;

use App\Pricing\QuoteEngine;
use Illuminate\Http\{JsonResponse, Request};
use App\Http\Controllers\Controller;

class QuoteController extends Controller
{
	/**
	 * Price an order without placing it. Open to guests: the order builder calls it as the customer picks options.
	 */
	public function store(Request $request, QuoteEngine $engine): JsonResponse
	{
		$request->validate(['service' => ['required', 'string']]);

		$quote = $engine->quote($request->string('service')->toString(), $request->except('service'));

		return response()->json(['data' => $quote->toArray()]);
	}
}
