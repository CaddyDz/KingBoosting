<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateOrderRequest;
use App\Order;
use App\ServiceTypePivot;

class OrdersController extends Controller
{
	/**
	 * Display a listing of the order.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new order.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created order in storage.
	 *
	 * @param  \App\Http\Requests\CreateOrderRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CreateOrderRequest $request)
	{
		// Create the order and the order details
		$service = ServiceTypePivot::find($request->service)->type->type;
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

	/**
	 * Display the specified order.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified order.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified order in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified order from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
}
