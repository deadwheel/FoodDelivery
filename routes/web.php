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

Route::get('/', function () {
    return view('welcome');
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/roles_create','roles_create@create_roles');


Route::get('/attach','assign_user@attach_role');

Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function() {
    Route::get('/', 'admin\list_users@list');
    Route::get('/users/edit/{id}', 'admin\list_users@edit');
    Route::resource('users','admin\list_users');
    Route::resource('roles', 'admin\Roles');
    Route::resource('offers', 'admin\Offers');
});


Route::group(['prefix' => 'waitress', 'middleware' => ['role:waitress|admin']], function() {
    Route::resource('orders', 'waitress\Orders');
    Route::post('/orders/test', 'waitress\Orders@test');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
