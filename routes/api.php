<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('set-guest-token','API\ApiController@setGuestToken');
Route::post('get-tables','API\ApiController@getTables');
Route::post('products','API\ApiController@getProducts');
Route::post('get-charges','API\ApiController@getCharges');
Route::post('place-order','API\ApiController@placeOrder');
Route::post('make-table-free','API\ApiController@markTableAsFree');
Route::post('delete-order-item','API\ApiController@removeOrderItem');

// Admin
Route::post('get-orders','Admin\DashboardController@fetchTodayOrder');
Route::post('change-order-status','Admin\DashboardController@changeOrderStatus');
