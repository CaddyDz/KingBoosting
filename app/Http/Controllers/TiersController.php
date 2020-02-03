<?php

namespace App\Http\Controllers;

use App\Tier;
use App\Service;
use Illuminate\Http\Request;

class TiersController extends Controller
{
    /**
     * Display a listing of the tier.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Service $service)
    {
        return Tier::with('divisions')->get();
    }

    /**
     * Show the form for creating a new tier.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created tier in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified tier.
     *
     * @param  \App\Tier  $tier
     * @return \Illuminate\Http\Response
     */
    public function show(Tier $tier)
    {
        //
    }

    /**
     * Show the form for editing the specified tier.
     *
     * @param  \App\Tier  $tier
     * @return \Illuminate\Http\Response
     */
    public function edit(Tier $tier)
    {
        //
    }

    /**
     * Update the specified tier in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tier  $tier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tier $tier)
    {
        //
    }

    /**
     * Remove the specified tier from storage.
     *
     * @param  \App\Tier  $tier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tier $tier)
    {
        //
    }
}
