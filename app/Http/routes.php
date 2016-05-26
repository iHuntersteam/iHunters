<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api/admin'], function () {
    Route::get('persons', 'Person\PersonController@show');
    Route::post('persons', 'Person\PersonController@store');
    Route::put('persons', 'Person\PersonController@update');
    Route::delete('persons', 'Person\PersonController@destroy');
});

Route::group(['prefix' => 'api'], function () {
    Route::get('statistics', 'Statistics\StatisticController@show');
});
