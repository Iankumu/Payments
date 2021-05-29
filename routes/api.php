<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BraintreeController;
use App\Http\Controllers\MpesaSTKPUSHController;

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

// Mpesa STK Push Routes
Route::post('v1/mpesatest/stk/push', [MpesaSTKPUSHController::class, 'STKPush']);
Route::post('v1/mpesatest/stk/confirm', [MpesaSTKPUSHController::class, 'STKConfirm']);

// Braintree Routes
Route::get('braintree/generate_token', [BraintreeController::class, 'generateToken']);
Route::post('braintree', [BraintreeController::class, 'BrainTree']);
