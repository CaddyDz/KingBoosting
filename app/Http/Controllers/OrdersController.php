<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Stripe\Charge;
use App\Models\{Order, User};
use Swift_TransportException;
use App\Notifications\OrderPlaced;
use App\Http\Requests\OrderRequest;
use App\Mail\Receipt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class OrdersController extends Controller
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
			\Stripe\PaymentIntent::create([
				'amount' => round($request->price * 100),
				'currency' => 'eur',
				// Verify your integration in this guide by including this parameter
				'metadata' => ['integration_check' => 'accept_a_payment'],
			]);
			Charge::create([
				"amount" => round($request->price * 100),
				"currency" => "eur",
				"source" => $request->token,
				"description" => "Charge for " . auth()->user()->email,
			]);
			$order = $this->createOrder($request);
			if ($request->booster) { // if member have chosen a booster
				$booster = User::where('username', $request->booster)->firstOrFail(); // Get that booster model
				$order->booster_id = $booster->id; // Assign the order to them
				$order->status = 'progress';
				$order->save(); // Save the change
				$booster->notify(new OrderPlaced($order)); // Notify that booster
			} else { // Otherwise
				$users = User::role('Booster')->get(); // Get all boosters
				Notification::send($users, new OrderPlaced($order)); // Notify them all about the newly placed order
			}
			// Send receipt to client
			try {
				Mail::to($order->client)->send(new Receipt($order));
			} catch (Swift_TransportException $exception) {
				logger()->error($exception->getMessage());
				return response([
					'message' => __('No internet connection detected'),
				], 599);
			}
			return response([
				'message' => __('Your order has been placed'),
				'order_id' => $order->id,
			]);
		} catch (\Exception $ex) {
			logger()->error($ex->getMessage());
			return response([
				'error' => __('Purchase failed!'),
			], 402);
		}
	}

	private function createOrder(OrderRequest $request): Order
	{
		return Order::create([
			'purchase' => $request->purchase,
			'service' => $request->service,
			'summoner' => $request->nickname,
			'server' => request('server'), // property name already taken
			'options' => $request->options,
			'price' => $request->price,
			'comment' => $request->comment,
		]);
	}
}
