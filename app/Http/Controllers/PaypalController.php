<?php

namespace App\Http\Controllers;

use App\Models\Paypal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class PaypalController extends Controller
{

    public $base_url;

    public function __construct()
    {
        $this->base_url = config('services.paypal.environment') == 'sandbox'
            ? "https://api-m.sandbox.paypal.com"
            : "https://api-m.paypal.com";
    }

    // Used for testing
    public function view()
    {
        return Inertia::render('Payments/Paypal');
    }

    // Generate Access Token to be used in the transactions
    public function initialize()
    {
        $client_id = config('services.paypal.clientID');
        $client_secret = config('services.paypal.clientSecret');
        $url = $this->base_url . '/v1/oauth2/token';

        $response = Http::withBasicAuth($client_id, $client_secret)
            ->asForm()
            ->post($url, [
                'grant_type' => 'client_credentials'
            ]);


        $result = json_decode($response);
        return data_get($result, 'access_token');
    }

    // Create a Transaction
    public function PayPal(Request $request)
    {
        // return $this->initialize();
        $amount = $request->input('amount');
        $url = $this->base_url . '/v2/checkout/orders';
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

        $response = Http::withToken($this->initialize())->asJson()->post($url, $data);
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
                'authorization: Bearer ' . $this->initialize(),
                'content-type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);


        $result = json_decode($response);

        return $result;

    }
    // Process the transaction
    public function Transaction(Request $request)
    {

        $token  = $request->query('token');


        $url = $this->base_url . "/v2/checkout/orders/" . $token . "/capture";


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
                'authorization: Bearer ' . $this->initialize(),
                'content-type: application/json'
            ),
        ));

        $response = curl_exec($curl);


        curl_close($curl);


        $result = json_decode($response, true);

        // Get the payer's names from the paypal's response
        $firstname = $result['payer']["name"]['given_name'];
        $surname = $result['payer']['name']['surname'];

        // Get various transaction details from the response from paypal
        $paypal_transaction = [
            'Transaction_id' => $result['id'],
            'status' => $result['status'],
            'payer_name' => $firstname . ' ' . $surname,
            'email' => $result['payer']['email_address'],
            'payer_id' => $result['payer']['payer_id'],
            'payer_country_code' => $result['payer']['address']['country_code'],
            'reference_id' => $result['purchase_units'][0]['reference_id'],
            'payment_id' => $result['purchase_units'][0]['payments']['captures'][0]['id'],
            'payment_status' => $result['purchase_units'][0]['payments']['captures'][0]['status'],
            'currency_code' => $result['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'],
            'amount' => $result['purchase_units'][0]['payments']['captures'][0]['amount']['value'],
            'paypal_fee' => $result['purchase_units'][0]['payments']['captures'][0]['seller_receivable_breakdown']['paypal_fee']['value'],
            'net_amount' => $result['purchase_units'][0]['payments']['captures'][0]['seller_receivable_breakdown']['net_amount']['value']
        ];
        // Add the paypal transaction details to the Database
        Paypal::create($paypal_transaction);

        return Inertia::render('Payments/Paypal', [
            'response' => $result
        ]);
    }
}
