<?php

namespace App\Http\Controllers;

use Braintree\Gateway;
use Illuminate\Http\Request;

class BraintreeController extends Controller
{
    public static function Gateway()
    {
        $gateway = new Gateway([
            'environment' => env('BT_ENVIRONMENT'),
            'merchantId' => env('BT_MERCHANT_ID'),
            'publicKey' => env('BT_PUBLIC_KEY'),
            'privateKey' => env('BT_PRIVATE_KEY')
        ]);
        return $gateway;
    }

    public function generateToken()
    {
        $gateway = $this->Gateway();
        $clientToken = $gateway->clientToken()->generate();
        return $clientToken;
    }

    public function BrainTree(Request $request)
    {

        $payment_nonce = $request->input('nonce');
        $amount = $request->input('amount');
        $fname = $request->input('fname');
        $lname = $request->input('lname');
        $email = $request->input('email');
        // Initializing Braintree
        $gateway = $this->Gateway();

        // Get Payment Nonce
        $nonceFromTheClient = $payment_nonce;

        // Creating a Transaction
        $result = $gateway->transaction()->sale([
            'amount' => $amount,
            'paymentMethodNonce' => $nonceFromTheClient,
            'customer' => [
                'firstName' => $fname,
                'lastName' => $lname,
                'email' => $email
            ],
            'options' => [
                'submitForSettlement' => True
            ]
        ]);
        if ($result->success) {
            $transaction = $result->transaction;
            //        header("Location: " . $baseUrl . "transaction.php?id=" . $transaction->id);
            return back()->with('success_message', 'Transaction Successful . The ID is: ' . $transaction->id);
        } else {
            $errorString = "";

            foreach ($result->errors->deepAll() as $error) {
                $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
            }

            //        $_SESSION["errors"] = $errorString;
            //        header("Location: " . $baseUrl . "index.php");
            return back()->withErrors('An Error occured with the message: ' . $result->message);
        }
    }

    public function Frontend()
    {
        return view('welcome', ['token' => $this->generateToken()]);
    }
}
