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


Route::match(['post','get'],'/guests', [
    'as' => 'guests',
    'uses' => 'GuestsController@list'
]);

Route::get('/{search?}', [
    'as' => 'landing',
    'uses' => 'GuestsController@landing'
]);


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
