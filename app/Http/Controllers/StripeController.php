<?php

namespace App\Http\Controllers;


use Stripe\Stripe;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public $domain_name = env('FRONTEND_URL');

    public function Stripe(Request $request)
    {
        $amount = $request->input('amount');
        $name = $request->input('name');

        Stripe::setApiKey(env('STRIPE_API_KEY'));
        header('Content-Type: application/json');
        $checkout_session =  Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $this->format_amount($amount), //Smallest Denomination. In this case it is worth $20
                    'product_data' => [
                        'name' => $name,
                        'images' => ["https://i.imgur.com/EHyR2nP.png"],
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->domain_name . '/success',
            'cancel_url' =>  $this->domain_name . '/cancel',
        ]);
        echo json_encode(['id' => $checkout_session->id]);
    }

    public function line_items()
    {
    }
    public function format_amount($amount)
    {
        return $amount * 100; //This case it is in USD
    }
}
