<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use Stripe\Charge;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(OrderRequest $request)
	{
		// Set your secret key. Remember to switch to your live secret key in production!
		// See your keys here: https://dashboard.stripe.com/account/apikeys
		\Stripe\Stripe::setApiKey('sk_test_fvYVXcTxZMYsXqsy7fK7VLOH003D2eLbhf');

		try {
			$intent = \Stripe\PaymentIntent::create([
				'amount' => $request->price * 100,
				'currency' => 'eur',
				// Verify your integration in this guide by including this parameter
				'metadata' => ['integration_check' => 'accept_a_payment'],
			]);
			Charge::create([
				"amount" => $request->price * 100,
				"currency" => "eur",
				"source" => $request->stripeToken,
				"description" => "Charge for " . auth()->user()->email,
			]);
			$order = Order::create([
				'service' => $request->service,
				'tier' => $request->tier,
				'division' => $request->division,
				'server' => request('server'),
				'wins' => $request->wins,
				'queue' => $request->queue,
				'booster_id' => $request->booster,
				'client_id' => auth()->id(),
				'options' => $request->options,
				'price' => $request->price,
			]);
			if ($request->booster) {
				$booster = User::where('username', $request->booster)->firstOrFail();
				$booster->notify(new OrderPlaced($order));
			} else {
				$users = User::role('Booster')->get();
				Notification::send($users, new OrderPlaced($order));
			}
			return response([
				'message' => __('Your order has been placed'),
			]);
		} catch (\Exception $ex) {
			logger()->error($ex->getMessage());
			return response([
				'error' => __('Purchase failed!'),
			], 402);
		}
	}
}
