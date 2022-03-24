<?php

namespace App\Http\Controllers;

use App\Traits\Mpesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MPESAB2CController extends Controller
{
    use Mpesa;
    public function simulate(Request $request)
    {
        $url = env('MPESA_ENVIRONMENT') == 'sandbox'
            ? 'https://sandbox.safaricom.co.ke/mpesa/b2c/v1/paymentrequest'
            : 'https://api.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';

        $phoneno = $request->input('phonenumber');
        $amount = $request->input('amount');
        $remarks = $request->input('remarks');
        $occasion = $request->input('occasion');

        $body = [
            "InitiatorName" => env('MPESA_INITIATOR_NAME'),
            "SecurityCredential" => $this->generate_security_credential(),
            "CommandID" => "SalaryPayment", //can also be BusinessPayment or PromotionPayment
            "Amount" => $amount,
            "PartyA" => env('MPESA_B2C_SHORTCODE'),
            "PartyB" => $this->phoneValidator($phoneno),
            "Remarks" => $remarks,
            "QueueTimeOutURL" => route('b2c.timeout'),
            "ResultURL" => route('b2c.result'),
            "Occassion" => $occasion,
        ];

        $response = $this->MpesaRequest($url, $body);
        return $response;
    }

    public function result(Request $request)
    {
        Log::info("Result URL has been hit");
        Log::info($request->all());
    }
    public function timeout(Request $request)
    {
        Log::info("Timeout URL has been hit");
        Log::info($request->all());
    }
}
