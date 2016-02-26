<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::auth();
});

Route::group(['middleware' => ['web', 'auth.admin']], function () {
    Route::get('/home', 'HomeController@index');
    Route::resource('bank', 'BankController', ['except' => ['show']]);
});

/**
 * API Routes
 */
Route::group(['namespace' => 'APIs', 'prefix' => 'api/v1', 'as' => 'api.v1.', 'middleware' => 'api'], function() {
    Route::post('login', ['as' => 'login', 'uses' => 'AuthController@postLogin']);

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('user', 'AuthController@getUser');
    });

});