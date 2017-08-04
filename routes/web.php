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
Auth::routes();

Route::get('/logout',function(){

   Auth::logout();

   return redirect('/');
});



Route::group([
    'middleware' => 'auth'
], function () {

    Route::post('/import', [
        'as' => 'import',
        'uses' => 'GuestsController@importFile'
    ]);

    Route::any('/validate', [
        'as' => 'validate',
        'uses' => 'GuestsController@validateList'
    ]);

    Route::match(['post', 'get'], '/guests', [
        'as' => 'guests',
        'uses' => 'GuestsController@list'
    ]);

});

Route::get('/{search?}', [
    'as' => 'landing',
    'uses' => 'GuestsController@landing'
]);




//Route::get('/home', 'HomeController@index')->name('home');
