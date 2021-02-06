<?php

namespace App\Http\Controllers;

use App\Mpesa\Registar;
use App\Mpesa\TokenGenerator;
use Illuminate\Http\Request;

class MpesaController extends Controller
{

    //     public function index()
    //     {
    //         try {
    //             $env = 'sandbox';
    //             $config = config("Misc.mpesa.c2b.{$env}");
    //             $token = (new TokenGenerator())->generateToken($env);
    //             $confirmation_url = route('api.mpesac2bapi.mpesa.c2b.confirm', $config['confirmation_key']);
    //             $validation_url = route('api.mpesa.c2b.validate', $config['validation_key']);
    //             $short_code = $config['short_code'];



    //             $response =  (new Registar())->setShortCode($short_code)
    //                 ->setValidationUrl($validation_url)
    //                 ->setConfirmationUrl($confirmation_url)
    //                 ->setToken($token)
    //                 ->register($env);
    //         } catch (\ErrorException $e) {
    //             return $e->getMessage();
    //         }

    //         return $response;
    //     }
    // }
    public function generateAccessToken()
    {
        $consumer_key = "tasnZFjVvqE9BD1KFSytuuGANaT7G2iP";
        $consumer_secret = "flSZ7bItZ36T0z9o";
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
}
