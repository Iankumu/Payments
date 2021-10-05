<?php

namespace App\Http\Controllers;

use App\Mpesa\STKPush;
use App\Models\MpesaSTK;
use App\Traits\Mpesa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class MpesaSTKPUSHController extends Controller
{
    use Mpesa;
    public $result_code = 1;
    public $result_desc = 'An error occured';

    // Initiate  Stk Push Request
    public function STKPush(Request $request)
    {

        $amount = $request->input('amount');
        $phoneno = $request->input('phonenumber');

        // Some validations for the phonenumber to format it to the required format
        $phoneno = (substr($phoneno, 0, 1) == "+") ? str_replace("+", "", $phoneno) : $phoneno;
        $phoneno = (substr($phoneno, 0, 1) == "0") ? preg_replace("/^0/", "254", $phoneno) : $phoneno;
        $phoneno = (substr($phoneno, 0, 1) == "7") ? "254{$phoneno}" : $phoneno;

        $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $this->generateAccessToken()));
        $curl_post_data = [
            //Fill in the request parameters with valid values
            'BusinessShortCode' => env('MPESA_BUSINESS_SHORTCODE'), //Has to be a paybill and not a till number since it is not supported
            'Password' => $this->lipaNaMpesaPassword(),
            'Timestamp' => Carbon::rawParse('now')->format('YmdHms'),
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phoneno, // replace this with your phone number
            'PartyB' =>  env('MPESA_BUSINESS_SHORTCODE'),
            'PhoneNumber' => $phoneno, // replace this with your phone number
            'CallBackURL' => route('mpesa.confirm'), //url should be https and should not contain keywords such as mpesa,safaricom etc
            'AccountReference' => "Testing", //Account Number to a paybill..Maximum of 12 Characters.
            'TransactionDesc' => "Payment" //Maximum of 13 Characters.
        ];
        // Encodes the array to a json string while escaping the multiple foward slashes in the callback url
        $data_string = json_encode($curl_post_data, JSON_UNESCAPED_SLASHES);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);
        $response = json_decode($curl_response, true);
        // Store the merchant request id and the checkout id to the database
        // This will be used to verify the response from Safaricom once the transaction is successful
        MpesaSTK::create([
            'merchant_request_id' =>  $response['MerchantRequestID'],
            'checkout_request_id' =>  $response['CheckoutRequestID']
        ]);
        return $response;
    }

    // This function is used to review the response from Safaricom once a transaction is complete
    public function STKConfirm(Request $request)
    {
        $stk_push_confirm = (new STKPush())->confirm($request);

        if ($stk_push_confirm) {

            $this->result_code = 0;
            $this->result_desc = 'Success';
        }
        return response()->json([
            'ResultCode' => $this->result_code,
            'ResultDesc' => $this->result_desc
        ]);
    }


    // Used to query the status of an STK Push Transaction
    public function query(Request $request)
    {
        $checkoutRequestId = $request->input('CheckoutRequestID');
        $curl_post_data = [
            'BusinessShortCode' => env('MPESA_BUSINESS_SHORTCODE'),
            'Password' => $this->lipaNaMpesaPassword(),
            'Timestamp' => Carbon::rawParse('now')->format('YmdHms'),
            'CheckoutRequestID' => $checkoutRequestId
        ];

        $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';
        $curl_response = $this->MpesaRequest($url,$curl_post_data);
        $response = json_decode($curl_response, true);
        return $response;
    }
}
