<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


class PaypalController extends Controller
{

    // Generate Access Token to be used in the transactions
    public function initialize()
    {
        $client_id = env('PAYPAL_CLIENT_ID');
        $client_secret = env('PAYPAL_CLIENT_SECRET');
        $url = 'https://api.sandbox.paypal.com/v1/oauth2/token';

        $curl = curl_init();
        $credentials = base64_encode($client_id . ":" . $client_secret);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "grant_type=client_credentials",
            CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                'accept-language: en_US',
                "Authorization: Basic " . $credentials,
                'content-type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            Log::error($err);
        } else {
            $result = json_decode($response, true);
            return $result;
        }
    }

    // Create a Transaction
    public function PayPal(Request $request)
    {
        $amount = $request->input('amount');
        $url = 'https://api.sandbox.paypal.com/v2/checkout/orders';
        $curl = curl_init();

        $data = [
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "reference_id" => "PUHF",
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $amount
                    ]
                ]
            ],
            "application_context" => [
                "return_url" => url('/') . "/paypal/transaction",
                "cancel_url" => url('/') . "/cancel"
            ]
        ];
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                'accept-language: en_US',
                'authorization: Bearer ' . $this->initialize()['access_token'],
                'content-type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            Log::error($err);
        } else {
            $result = json_decode($response, true);
            // return redirect($result['links'][1]['href']);
            // $this->Transaction($result['id']);
            return $result;
        }
    }
    // Process the transaction
    public function Transaction($id)
    {

        // $id = $request->input('id');
        $url = "https://api.sandbox.paypal.com/v2/checkout/orders/" . $id . "/capture";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                'authorization: Bearer ' . $this->initialize()['access_token'],
                'content-type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            Log::error($err);
        } else {
            $result = json_decode($response, true);
            return $result;
        }
    }

    public function approve(Request $request)
    {
        $approve = $request->get('approve');

        $response = Http::get($approve);
        return $response;
    }



    public function view()
    {
        return view('paypal');
    }
}
