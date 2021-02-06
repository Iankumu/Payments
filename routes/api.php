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


// Route::post('v1/access/token', '\App\Http\Controllers\MpesaSTKPUSHController@generateAccessToken');

Route::post('v1/mpesatest/stk/push', '\App\Http\Controllers\MpesaSTKPUSHController@STKPush');



// Route::group(['prefix' => 'api', 'as' => 'api.mpesa', 'namespace' => 'API\V1\Payments\Mpesa'], function () {
//     Route::group(['as' => 'c2b'], function () {
//         Route::get('m-trx/confirmation/{confirmation_key}', 'API\V1\Payments\Mpesa\C2bController@confirmTrx')->name('api.mpesa.c2b.confirm');
//         Route::get('m-trx/validate/{validation_key}', 'API\V1\Payments\Mpesa\C2bController@validateTrx')->name('api.mpesa.c2b.validate');
//     });
// });
