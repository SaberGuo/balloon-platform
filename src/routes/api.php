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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::resource('device', 'DeviceController', ['only' => [
    'index', 'show', 'store', 'update', 'destroy'
]]);

Route::resource('device/{device}/data', 'DeviceDataController', ['only' => [
    'index'
]]);

Route::resource('device/{device}/config', 'DeviceConfigController', ['only' => [
    'index', 'show', 'store', 'update'
]]);
