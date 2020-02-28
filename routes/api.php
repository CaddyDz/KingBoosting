<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Auth::routes(['verify' => true]);
// List all tiers
Route::post('/tiers', 'TiersController@index');
// List all servers
Route::get('/servers', 'ServersController@index');
// List all services
Route::get('/services', 'ServicesController@index');
Route::get('/services/{service}', 'ServicesController@show');
// Get the kind of a service by the service id
Route::get('/services/{id}/kind', 'ServicesController@getKind');
// Get all features
Route::get('/features', 'FeaturesController@index');
// Get all priorities
Route::get('/priorities', 'PrioritiesController@index');
// List an array of boosters usernames
Route::get('/getBoostersNames', 'BoostersController@getBoostersNames');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
