<?php

namespace App\Http\Controllers;

use App\Server;
use Illuminate\Http\Request;

class ServersController extends Controller
{
	/**
	 * Display a listing of the servers.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function index()
	{
		return Server::all();
	}

	/**
	 * Show the form for creating a new server.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created server in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified server.
	 *
	 * @param  \App\Server  $server
	 * @return \Illuminate\Http\Response
	 */
	public function show(Server $server)
	{
		//
	}

	/**
	 * Show the form for editing the specified server.
	 *
	 * @param  \App\Server  $server
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Server $server)
	{
		//
	}

	/**
	 * Update the specified server in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Server  $server
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Server $server)
	{
		//
	}

	/**
	 * Remove the specified server from storage.
	 *
	 * @param  \App\Server  $server
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Server $server)
	{
		//
	}
}
