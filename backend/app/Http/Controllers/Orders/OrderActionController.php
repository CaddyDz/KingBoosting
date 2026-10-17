<?php

declare(strict_types=1);

namespace App\Http\Controllers\Orders;

use Illuminate\Http\Request;
use App\Models\{Order, User};
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Actions\Payouts\PayOutBooster;
use App\Actions\Orders\{AssignOrder, ClaimOrder, CompleteOrder, PauseOrder, ReleaseOrder, ResumeOrder, UpdateOrderCredentials};

/**
 * One endpoint per order action. The action classes check OrderPolicy: 403 when the user may never
 * do this, 409 when the order's status has moved on, and 422 for invalid input.
 */
class OrderActionController extends Controller
{
	public function claim(Request $request, Order $order, ClaimOrder $action): OrderResource
	{
		return $this->respond($action->handle($this->user($request), $order));
	}

	public function assign(Request $request, Order $order, AssignOrder $action): OrderResource
	{
		// Refuse before validating, so someone who may not assign never learns what input is expected
		$this->authorize('assign', $order);

		$validated = $request->validate([
			'booster_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', 'booster')],
		]);

		return $this->respond($action->handle($this->user($request), $order, User::query()->findOrFail($validated['booster_id'])));
	}

	public function pause(Request $request, Order $order, PauseOrder $action): OrderResource
	{
		return $this->respond($action->handle($this->user($request), $order));
	}

	public function resume(Request $request, Order $order, ResumeOrder $action): OrderResource
	{
		return $this->respond($action->handle($this->user($request), $order));
	}

	public function release(Request $request, Order $order, ReleaseOrder $action): OrderResource
	{
		return $this->respond($action->handle($this->user($request), $order));
	}

	public function complete(Request $request, Order $order, CompleteOrder $action): OrderResource
	{
		return $this->respond($action->handle($this->user($request), $order));
	}

	/**
	 * Pay out one completed order on its own: it still gets a Payout, so the booster's history is complete.
	 */
	public function markPaidOut(Request $request, Order $order, PayOutBooster $payOut): OrderResource
	{
		$this->authorize('markPaidOut', $order);

		/** @var User $booster */
		$booster = $order->booster;
		$payOut->handle($this->user($request), $booster, [$order->id]);

		return $this->respond($order->refresh());
	}

	public function updateCredentials(Request $request, Order $order, UpdateOrderCredentials $action): OrderResource
	{
		$this->authorize('updateCredentials', $order);

		$validated = $request->validate([
			'login' => ['required', 'string', 'max:255'],
			'password' => ['required', 'string', 'max:255'],
		]);

		return $this->respond($action->handle($this->user($request), $order, $validated['login'], $validated['password']));
	}

	private function user(Request $request): User
	{
		/** @var User */
		return $request->user();
	}

	private function respond(Order $order): OrderResource
	{
		return (new OrderResource($order->load(['customer', 'booster'])))->withDetails();
	}
}
