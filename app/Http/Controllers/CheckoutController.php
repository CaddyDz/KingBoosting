<?php

declare(strict_types=1);

namespace App\Http\Controllers;


class CheckoutController extends Controller
{
	public function intent()
	{
		// Set your secret key. Remember to switch to your live secret key in production!
		// See your keys here: https://dashboard.stripe.com/account/apikeys
		\Stripe\Stripe::setApiKey('sk_test_fvYVXcTxZMYsXqsy7fK7VLOH003D2eLbhf');

		$intent = \Stripe\PaymentIntent::create([
			'amount' => 1099,
			'currency' => 'eur',
			// Verify your integration in this guide by including this parameter
			'metadata' => ['integration_check' => 'accept_a_payment'],
		]);
		echo json_encode(['client_secret' => $intent->client_secret]);
	}
}
