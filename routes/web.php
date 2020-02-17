<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::view('/', 'welcome');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

// Wildcard should be the last, this helps for SEO
Route::get('{service}', 'ServicesController@show')
    ->name('service')->where('service', '^(?!dashboard)[a-zA-Z0-9-_]*$');
