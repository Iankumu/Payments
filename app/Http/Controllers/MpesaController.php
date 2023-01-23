<?php

namespace App\Http\Controllers;

use Iankumu\Mpesa\Facades\Mpesa;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class MpesaController extends Controller
{
    public function index()
    {
        return Inertia::render('Payments/Mpesa');
    }

    public function stkpush()
    {
        return Inertia::render('Payments/Partials/Stkpush');
    }
    public function c2b()
    {
        return Inertia::render('Payments/Partials/C2B');
    }
    public function b2c()
    {
        return Inertia::render('Payments/Partials/B2C');
    }
    public function accountBalance()
    {
        return Inertia::render('Payments/Partials/AccountBalance');
    }
    public function transactionStatus()
    {
        return Inertia::render('Payments/Partials/TransactionStatus');
    }
    public function reversals()
    {
        return Inertia::render('Payments/Partials/Reversals');
    }

    public function simulate_balance(Request $request)
    {
        $shortcode = $request->input('shortcode');
        $identifier = $request->input('identiertype');
        $remarks = $request->input('remarks');
        $response=Mpesa::accountBalance($shortcode,$identifier,$remarks);

        $result = json_decode((string)$response);

        return Inertia::render('Payments/Partials/AccountBalance',[
            'response'=>$result
        ]);
    }
    public function simulate_status(Request $request)
    {
        $shortcode = $request->input('shortcode');
        $identifier = $request->input('identiertype');
        $transactionid = $request->input('transactionid');
        $remarks = $request->input('remarks');
        $response=Mpesa::transactionStatus($shortcode,$transactionid,$identifier,$remarks);

        $result = json_decode((string)$response);

        return Inertia::render('Payments/Partials/TransactionStatus',[
            'response'=>$result
        ]);
    }
    public function simulate_reversals(Request $request)
    {
        $shortcode = $request->input('shortcode');
        $transactionid = $request->input('transactionid');
        $amount = $request->input('amount');
        $remarks = $request->input('remarks');
        $response=Mpesa::reversal($shortcode,$transactionid,$amount,$remarks);

        $result = json_decode((string)$response);

        return Inertia::render('Payments/Partials/Reversals',[
            'response'=>$result
        ]);
    }

    public function result(Request $request)
    {
        Log::info('Results endpoint has been hit');
        Log::info($request->all());
    }
    public function timeout(Request $request)
    {
        Log::info('Timeout endpoint has been hit');
        Log::info($request->all());
    }
}
