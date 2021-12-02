<?php

namespace App\Http\Controllers;

use App\Models\MpesaC2B;
use App\Traits\Mpesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class MPESAC2BController extends Controller
{
    use Mpesa;

    public function registerURLS(Request $request)
    {
        $shortcode = $request->input('shortcode');
        $body = [
            "ShortCode" => (int)$shortcode,
            "ResponseType"=> "Completed",
            "ConfirmationURL"=>route('c2b.confirm'),//url should be https and should not contain keywords such as mpesa,safaricom etc
            "ValidationURL"=> route('c2b.validate'),//url should be https and should not contain keywords such as mpesa,safaricom etc
        ];
        $url = env('MPESA_ENVIRONMENT') == 'sandbox'
        ?'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl'
        :'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl';

        $response = $this->MpesaRequest($url,$body);
        return $response;
    }

    public function simulate(Request $request)
    {
        $phonenumber = $request->input('phonenumber');
        $amount = $request->input('amount');
        $account = $request->input('account');
        $shortcode = $request->input('shortcode');

        $data = [
            'Msisdn'=>$this->phoneValidator($phonenumber),
            'Amount'=>(int) $amount,
            'BillRefNumber'=>$account, //Account number for a paybill
            'CommandID'=>'CustomerPayBillOnline', //Can also be CustomerBuyGoodsOnline for a till number
            'ShortCode'=> $shortcode// Paybill or Till Number
        ];
        // dd($data);
        $url =env('MPESA_ENVIRONMENT') == 'sandbox'
        ?'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate'
        :'https://api.safaricom.co.ke/mpesa/c2b/v1/simulate';

        $response = $this->MpesaRequest($url,$data);
        return $response;
    }

    public function validation(Request $request)
    {
        Log::info('Validation endpoint has been hit');
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
        $c2b->MiddleName = $payload['MiddleName'];
        $c2b->LastName = $payload['LastName'];
        $c2b->save();

        return response($payload);
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
        $c2b->MiddleName = $payload['MiddleName'];
        $c2b->LastName = $payload['LastName'];
        $c2b->save();

        return response($payload);
    }
}
