<?php

declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Models\Order;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Actions\Orders\ConfirmPayment;
use App\Payments\OfflinePaymentGateway;
use Illuminate\Support\Facades\Process;

/**
 * Local shortcuts for the end-to-end tests, and for trying checkout without Stripe keys (routes/testing.php).
 */
class SimulationController extends Controller
{
	/**
	 * What Stripe's payment_intent.succeeded webhook does, for an order paid offline.
	 */
	public function pay(Order $order, ConfirmPayment $confirm): OrderResource
	{
		$this->authorize('pay', $order);
		abort_unless(OfflinePaymentGateway::handles($order), 409, 'Only offline payments can be simulated.');

		return (new OrderResource($confirm->handle($order)->load(['customer', 'booster'])))->withDetails();
	}

	/**
	 * Back to the seeded demo, before an end-to-end run. A separate process, because Reverb registers its
	 * commands (demo:reset restarts it) only in the console.
	 */
	public function reset(): Response
	{
		Process::path(base_path())->timeout(120)->run(['php', 'artisan', 'demo:reset', '--force'])->throw();

		return response()->noContent();
	}
}
