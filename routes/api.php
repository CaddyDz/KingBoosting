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
Route::post('/tiers', 'TiersController@index');
Route::get('/servers', 'ServersController@index');
Route::get('/services', 'ServicesController@index');
Route::get('/services/{id}/kind', 'ServicesController@getKind');
Route::get('/features', 'FeaturesController@index');
Route::get('/priorities', 'PrioritiesController@index');
Route::get('/getBoostersNames', 'BoostersController@getBoostersNames');
