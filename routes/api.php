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
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

// Routes to set password for the user who just verified their email
Route::middleware(['auth', 'verified', 'nopassword'])->name('password.')->group(function () {
    Route::get('set-password', 'Auth\VerificationController@showPasswordSettingForm')->name('show');
    Route::post('set-password', 'Auth\VerificationController@setPassword')->name('store');
});
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
// List all articles
Route::get('/blog/first', 'ArticlesController@getFirstArticle');
Route::get('/blog/count', 'ArticlesController@getArticlesCount');
Route::get('/blog/{article}', 'ArticlesController@show')->name('article');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
