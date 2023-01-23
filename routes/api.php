<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BraintreeController;
use App\Http\Controllers\MPESAC2BController;
use App\Http\Controllers\MpesaSTKPUSHController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\MPESAB2CController;
use App\Http\Controllers\MpesaController;

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
Route::post('v1/confirm', [MpesaSTKPUSHController::class, 'STKConfirm'])->name('mpesa.confirm');
Route::post('v1/callback/query', [MpesaSTKPUSHController::class, 'query']);

// Braintree Routes
Route::get('braintree/generate_token', [BraintreeController::class, 'generateToken']);
Route::post('braintree', [BraintreeController::class, 'BrainTree']);


//Paypal Routes
Route::post('paypal/create', [PaypalController::class, 'PayPal']);
Route::post('paypal/transaction', [PaypalController::class, 'Transaction']);

//MPESA C2B

Route::post('validation', [MPESAC2BController::class, 'validation'])->name('c2b.validate');
Route::post('confirmation', [MPESAC2BController::class, 'confirmation'])->name('c2b.confirm');

// MPESA B2C
Route::post('v1/b2c/result', [MPESAB2CController::class, 'result'])->name('b2c.result');
Route::post('v1/b2c/timeout', [MPESAB2CController::class, 'timeout'])->name('b2c.timeout');

// MPESA Account Balance
Route::post('v1/balance/result', [MpesaController::class, 'result']);
Route::post('v1/balance/timeout', [MpesaController::class, 'timeout']);

// MPESA Transaction Status
Route::post('v1/status/result', [MpesaController::class, 'result']);
Route::post('v1/status/timeout', [MpesaController::class, 'timeout']);

// MPESA Reversals
Route::post('v1/reversals/result', [MpesaController::class, 'result']);
Route::post('v1/reversals/timeout', [MpesaController::class, 'timeout']);
