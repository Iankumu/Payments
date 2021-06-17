<?php

namespace App\Http\Controllers;

use App\Mpesa\STKPush;
use App\Models\MpesaSTK;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class MpesaSTKPUSHController extends Controller
{
    public $result_code = 1;
    public $result_desc = 'An error occured';
    // Generate an AccessToken using the Consumer Key and Consumer Secret
    public function generateAccessToken()
    {
        $consumer_key = env('MPESA_CONSUMER_KEY');
        $consumer_secret = env('MPESA_CONSUMER_SECRET');
        $credentials = base64_encode($consumer_key . ":" . $consumer_secret);
        $url = "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic " . $credentials));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        $access_token = json_decode($curl_response);
        return $access_token->access_token;
    }
    // Generate a base64  password using the Safaricom PassKey and the Business ShortCode to be used in the Mpesa Transaction
    public function LipaNaMpesaPassword()
    {
        $lipa_time = Carbon::rawParse('now')->format('YmdHms');
        $passkey = env('SAFARICOM_PASSKEY');
        $BusinessShortCode = env('MPESA_BUSINESS_SHORTCODE');
        $timestamp = $lipa_time;
        $lipa_na_mpesa_password = base64_encode($BusinessShortCode . $passkey . $timestamp);
        return $lipa_na_mpesa_password;
    }

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
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $this->generateAccessToken()));
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
            'CallBackURL' => env('MPESA_CALLBACK_URL') . '/api/v1/callback/confirm', //url should be https and should not contain keywords such as mpesa,safaricom etc
            'AccountReference' => "Testing", //Account Number to a paybill..Maximum of 12 Characters.
            'TransactionDesc' => "Payment" //Maximum of 13 Characters.
        ];
        $data_string = json_encode($curl_post_data);
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


    public function query(Request $request)
    {
        $checkoutRequestId = $request->input('CheckoutRequestID');
        $curl_post_data = [
            'BusinessShortCode' => env('MPESA_BUSINESS_SHORTCODE'),
            'Password' => $this->lipaNaMpesaPassword(),
            'Timestamp' => Carbon::rawParse('now')->format('YmdHms'),
            'CheckoutRequestID' => $checkoutRequestId
        ];

        $postdata = json_encode($curl_post_data);
        $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $this->generateAccessToken()));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $curl_response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($curl_response, true);
        return $response;
    }
}
