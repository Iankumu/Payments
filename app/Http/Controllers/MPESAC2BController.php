<?php

namespace App\Http\Controllers;

use App\Models\MpesaC2B;
use Iankumu\Mpesa\Facades\Mpesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class MPESAC2BController extends Controller
{

    public function registerURLS(Request $request)
    {
        $shortcode = $request->input('shortcode');
        $response = Mpesa::c2bregisterURLS($shortcode);
        $result = json_decode((string)$response);

        return $result;
    }

    public function simulate(Request $request)
    {
        $phonenumber = $request->input('phonenumber');
        $amount = $request->input('amount');
        $account = $request->input('account');
        $shortcode = $request->input('shortcode');
        $command = $request->input('command');

        if ($command == "CustomerPayBillOnline") {

            $response = Mpesa::c2bsimulate($phonenumber, $amount, $shortcode, $command, $account);
        } else {
            $response = Mpesa::c2bsimulate($phonenumber, $amount, $shortcode, $command);
        }

        $result = json_decode((string)$response);
        return $result;
    }

    public function validation()
    {
        Log::info('Validation endpoint has been hit');
        $result_code = "0";
        $result_description = "Accepted validation request";
        return Mpesa::validationResponse($result_code, $result_description);
    }
    public function confirmation(Request $request)
    {
        Log::info('Confirmation endpoint has been hit');
        $payload = $request->all();

        $c2b = new MpesaC2B();
        $c2b->Transaction_type = $payload['TransactionType'];
        $c2b->Transaction_ID = $payload['TransID'];
        $c2b->Transaction_Time = $payload['TransTime'];
        $c2b->Amount = $payload['TransAmount'];
        $c2b->Business_Shortcode = $payload['BusinessShortCode'];
        $c2b->Account_Number = $payload['BillRefNumber'];
        $c2b->Invoice_no = $payload['InvoiceNumber'];
        $c2b->Organization_Account_Balance = $payload['OrgAccountBalance'];
        $c2b->ThirdParty_Transaction_ID = $payload['ThirdPartyTransID'];
        $c2b->Phonenumber = $payload['MSISDN'];
        $c2b->FirstName = $payload['FirstName'];
        $c2b->save();

        return response($payload);
    }
}
