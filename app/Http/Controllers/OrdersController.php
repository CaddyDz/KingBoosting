<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateOrderRequest;
use App\Order;
use App\ServiceTypePivot;

class OrdersController extends Controller
{
	/**
	 * Store a newly created order in storage.
	 *
	 * @param  \App\Http\Requests\CreateOrderRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CreateOrderRequest $request)
	{
		// Create the order and the order details
		$order = new Order();
		$order->service_service_type_id = $request->service;
		$order->queue = $request->queue;
		$order->specific_champions = $request->specific_champions;
		$order->booster_id = $request->booster_id;
		$order->price = $request->price;
		if ($service === 'Win Boosting') {
			//
		}
		$order->purchase = "Something";
		$order->save();
	}
}
