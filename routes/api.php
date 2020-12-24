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
//routing for login register
Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');

//routing for product
Route::post('products', 'API\ProductController@createProduct');

//routing for customer
Route::post('addcustomer', 'API\UserController@AddCustomer');
Route::get('viewcustomer', 'API\UserController@ViewCustomer');

//routing for createrequest
Route::post('createrequest', 'API\UserController@CreateRequest');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    // return $request->user();
    Route::get('details', 'API\UserController@details');
});

Route::get('getusers','UserController@getusers');
