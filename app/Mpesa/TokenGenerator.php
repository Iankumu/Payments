<?php

namespace App\Mpesa;

use App\Mpesa\ValidatesEndPoints;

class TokenGenerator

{
    use ValidatesEndPoints;

    protected $default_endpoints = [
        'sandbox' => 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials',
        'live' => 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
    ];

    public function generateToken(string $env)
    {

        $h = new ValidatesEndPoints();
        $h->ValidatesEndpoints($env);

        $consumer_key = config("Misc.mpesa.c2b.{$env}.consumer_key");
        $consumer_secret = config("Misc.mpesa.c2b.{$env}.consumer_secret");


        if (!$consumer_key) {
            throw new \ErrorException('Consumer Key Missing');
        }

        if (!$consumer_secret) {
            throw new \ErrorException('Consumer Secret Missing');
        }


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->endpoint);
        $credentials = base64_encode($consumer_key . ':' . $consumer_secret);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $credentials)); //setting a custom header
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $curl_response = curl_exec($curl);

        return json_decode($curl_response)->access_token;
    }
}
