<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;
use Illuminate\Http\JsonResponse;

/**
 * The order changed under the user: someone else claimed it first, or its status moved on.
 */
class OrderConflict extends RuntimeException
{
	public function render(): JsonResponse
	{
		return response()->json(['message' => $this->getMessage()], 409);
	}
}
