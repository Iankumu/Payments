<?php

namespace App\Traits;

use Illuminate\Support\Carbon;

trait Mpesa{
// Generate an AccessToken using the Consumer Key and Consumer Secret
    public function generateAccessToken()
    {
        $consumer_key = env('MPESA_CONSUMER_KEY');
        $consumer_secret = env('MPESA_CONSUMER_SECRET');
        $credentials = base64_encode($consumer_key . ":" . $consumer_secret);

        $url =env('MPESA_ENVIRONMENT') == 'sandbox'
        ?"https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials"
        :"https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";

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
    public function MpesaRequest($url,$body)
    {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '. $this->generateAccessToken(),
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
    public function generate_public_key()
    {

        if(env('MPESA_ENVIRONMENT') == 'sandbox')
        {
            $pub_key=openssl_pkey_get_public(file_get_contents(public_path('SandboxCertificate.cer')));
            $keyData = openssl_pkey_get_details($pub_key);
            file_put_contents(public_path('key.pub'), $keyData['key']);
            return true;
        }
        else
        {
            $pub_key=openssl_pkey_get_public(file_get_contents(public_path('ProductionCertificate.cer')));
            $keyData = openssl_pkey_get_details($pub_key);
            file_put_contents(public_path('prod.pub'), $keyData['prod']);
            return true;
        }
    }

    public function generate_security_credential($pass)
    {
        if(env('MPESA_ENVIRONMENT') == 'sandbox')
        {
            $path = public_path('key.pub');
            $isExists = file_exists($path);
            if($isExists != 1)
            {
                $this->generate_public_key();
            }
            $pk = openssl_pkey_get_public($this->getPublicKey());
            openssl_public_encrypt($pass, $encrypted, $pk, OPENSSL_PKCS1_PADDING);
            return base64_encode($encrypted);
        }
        else
        {
            $path = public_path('prod.pub');
            $isExists = file_exists($path);
            if($isExists != 1)
            {
                $this->generate_public_key();
            }
            $pk = openssl_pkey_get_public($this->getPublicKey());
            openssl_public_encrypt($pass, $encrypted, $pk, OPENSSL_PKCS1_PADDING);
            return base64_encode($encrypted);
        }
    }

    public function getPublicKey()
    {
        if(env('MPESA_ENVIRONMENT') == 'sandbox')
        {
            $key = file_get_contents(public_path('key.pub'));
            return $key;
        }
        else
        {
            $key = file_get_contents(public_path('prod.pub'));
            return $key;
        }
    }


}
