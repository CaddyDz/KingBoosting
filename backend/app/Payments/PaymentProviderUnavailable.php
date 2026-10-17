<?php

declare(strict_types=1);

namespace App\Payments;

use RuntimeException;
use Illuminate\Http\JsonResponse;

/**
 * Stripe couldn't be reached or refused the request. The order stays unpaid and is cleaned up later.
 */
class PaymentProviderUnavailable extends RuntimeException
{
	public function render(): JsonResponse
	{
		return response()->json(['message' => 'Payments are unavailable right now. Please try again in a moment.'], 503);
	}
}
