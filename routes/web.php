<?php

use App\Http\Controllers\BraintreeController;
use App\Http\Controllers\MPESAB2CController;
use App\Http\Controllers\MPESAC2BController;
use App\Http\Controllers\MpesaController;
use App\Http\Controllers\MpesaSTKPUSHController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/braintree', [BraintreeController::class, 'Frontend']);
Route::get('/paypal', [PaypalController::class, 'view']);


Route::get('/mpesa', [MpesaController::class, 'index']);

Route::get('/mpesa/stkpush', [MpesaController::class, 'stkpush']);
Route::get('/mpesa/c2b', [MpesaController::class, 'c2b']);
Route::get('/mpesa/b2c', [MpesaController::class, 'b2c']);
Route::get('/mpesa/balance', [MpesaController::class, 'accountBalance']);
Route::get('/mpesa/status', [MpesaController::class, 'transactionStatus']);
Route::get('/mpesa/reversals', [MpesaController::class, 'reversals']);

Route::post('/v1/mpesatest/stk/push', [MpesaSTKPUSHController::class, 'STKPush']);
Route::post('/v1/b2c/simulate', [MPESAB2CController::class, 'simulate']);
Route::post('register-urls', [MPESAC2BController::class, 'registerURLS']);
Route::post('c2b/simulate', [MPESAC2BController::class, 'simulate']);

Route::post('account-balance', [MpesaController::class, 'simulate_balance']);
Route::post('transaction-status', [MpesaController::class, 'simulate_status']);
Route::post('reversals', [MpesaController::class, 'simulate_reversals']);

// Route::get('paypal/transaction', [PaypalController::class, 'Transaction']);


require __DIR__ . '/auth.php';
