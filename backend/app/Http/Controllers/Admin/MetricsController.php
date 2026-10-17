<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Support\Metrics;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class MetricsController extends Controller
{
	public function __invoke(Metrics $metrics): JsonResponse
	{
		$this->authorize('viewMetrics');

		return response()->json(['data' => [
			'revenue_per_week' => $metrics->revenuePerWeek(),
			'orders_by_status' => $metrics->ordersByStatus(),
			'average_completion_hours' => $metrics->averageCompletionHours(),
		]]);
	}
}
