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

//received tagID
Route::post('/v1/getTag', 'ApiController@getUserData');

//receives userID, productOrders[id, quantity]
Route::post('/v1/addOrder', 'ApiController@addOrder');

//Route::post('/v1/pusher/auth', 'ApiController@auth');
//receives tagID, userID, productOrders[id, quantity]
//Route::post('/v1/getTag', 'ApiController@getUserData');