<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get(/**
 * @param Request $request
 * @return mixed
 */
    '/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:api')->group(function() {

    Route::post('oauth/access_token', 'Auth\OAuth2Controller@issueToken');

});

Route::namespace('api')->group(function(){

	Route::post('/refresh', 'UserController@refresh');
	Route::post('/login','UserController@login');
	Route::post('/register','UserController@register');
	Route::post('/logout', 'UserController@logout');
	
	Route::middleware('auth:api')->group(function(){
		
		Route::get('/offers','OfferController@index');
		Route::post('/orders','OrderController@create');
		Route::get('/orders','OrderController@index');
		Route::put('/user_details', 'UserController@update_details');
      Route::get('/user_details', 'UserController@getDetails');
	
        Route::get('/user_orders/{id}', 'OrderController@orders_list_user');
        Route::get('/order_status/{id}/', 'OrderController@get_status_order');

            Route::group(['middleware' => ['role:driver']], function() {

                Route::get('/driver/orders/{active?}', 'driver\Driver@get_orders_by_id');
                Route::post('/driver/take_it/{id}', 'driver\Driver@take_it');
                Route::post('/driver/end_it/{id}', 'driver\Driver@status_delivered');
                Route::post('/driver/update_pos/{id}', 'driver\Driver@update_position');
				Route::post('/driver/cancel_it/{id}', 'driver\Driver@cancel_it');
				Route::get('/driver/get_active/', 'driver\Driver@get_active');


            });

        });
	
});

