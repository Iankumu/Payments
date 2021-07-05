<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BraintreeController;
use App\Http\Controllers\MpesaSTKPUSHController;
use App\Http\Controllers\PaypalController;

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
Route::post('v1/confirm', [MpesaSTKPUSHController::class, 'STKConfirm'])->name('mpesa.confirm');
Route::post('v1/callback/query', [MpesaSTKPUSHController::class, 'query']);

// Braintree Routes
Route::get('braintree/generate_token', [BraintreeController::class, 'generateToken']);
Route::post('braintree', [BraintreeController::class, 'BrainTree']);


//Paypal Routes
Route::post('paypal/create', [PaypalController::class, 'PayPal']);
Route::post('paypal/transaction/{id}', [PaypalController::class, 'Transaction']);
