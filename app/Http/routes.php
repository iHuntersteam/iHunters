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

Route::group(['prefix' => 'api'], function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::get('persons', 'Person\PersonController@show');
        Route::post('persons', 'Person\PersonController@store');
        Route::put('persons', 'Person\PersonController@update');
        Route::delete('persons', 'Person\PersonController@destroy');
        Route::get('sites', 'Site\SiteController@show');
        Route::post('sites', 'Site\SiteController@store');
        Route::put('sites', 'Site\SiteController@update');
        Route::delete('sites', 'Site\SiteController@destroy');
        Route::get('keywords', 'Keyword\KeywordController@show');
        Route::post('keywords', 'Keyword\KeywordController@store');
        Route::put('keywords', 'Keyword\KeywordController@update');
        Route::delete('keywords', 'Keyword\KeywordController@destroy');

        Route::group(['prefix' => 'user'], function () {
            Route::post('create', 'User\UserController@create');
            Route::post('update-password', 'User\UserController@updatePassword');
        });
    });

    Route::group(['prefix' => 'statistics'], function () {
        Route::get('/', 'Statistics\StatisticController@show');

        Route::group(['prefix' => 'crawler'], function () {
            Route::get('/', 'Statistics\StatisticsCrawlerController@show');
        });
    });
});

Route::auth();

Route::get('/home', 'HomeController@index');
