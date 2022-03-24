<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

trait Mpesa
{
    // Generate an AccessToken using the Consumer Key and Consumer Secret
    public function generateAccessToken()
    {
        $consumer_key = env('MPESA_CONSUMER_KEY');
        $consumer_secret = env('MPESA_CONSUMER_SECRET');
        $credentials = base64_encode($consumer_key . ":" . $consumer_secret);

        $url = env('MPESA_ENVIRONMENT') == 'sandbox'
            ? "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials"
            : "https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";

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

    // Common Curl Format Of The Mpesa APIs.
    public function MpesaRequest($url, $body)
    {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->generateAccessToken(),
            'Content-Type: application/json'
        ]);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

        $curl_response = curl_exec($ch);
        curl_close($ch);
        return $curl_response;
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

    public function phoneValidator($phoneno)
    {
        // Some validations for the phonenumber to format it to the required format
        $phoneno = (substr($phoneno, 0, 1) == "+") ? str_replace("+", "", $phoneno) : $phoneno;
        $phoneno = (substr($phoneno, 0, 1) == "0") ? preg_replace("/^0/", "254", $phoneno) : $phoneno;
        $phoneno = (substr($phoneno, 0, 1) == "7") ? "254{$phoneno}" : $phoneno;

        return $phoneno;
    }

    public function generate_security_credential()
    {
        if (env('MPESA_ENVIRONMENT') == 'sandbox') {
            $pubkey = File::get(public_path('SandboxCertificate.cer'));
        } else {
            $pubkey = File::get(public_path('ProductionCertificate.cer'));
        }
        openssl_public_encrypt(env('MPESA_INITIATOR_PASSWORD'), $output, $pubkey, OPENSSL_PKCS1_PADDING);
        return base64_encode($output);
    }

    public function validationResponse($result_code, $result_description)
    {
        $result = json_encode([
            "ResultCode" => $result_code,
            "ResultDesc" => $result_description
        ]);
        $response = new Response();
        $response->headers->set("Content-Type", "application/json; charset=utf-8");
        $response->setContent($result);
        return $response;
    }
}
