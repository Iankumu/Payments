<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BraintreeController;
use App\Http\Controllers\PaypalController;

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

Route::get('/', [BraintreeController::class, 'Frontend']);

Route::post('/checkout', [BraintreeController::class, 'BrainTree']);

Route::get('/paypal', [PaypalController::class, 'view']);
