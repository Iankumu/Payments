<?php

namespace App\Http\Controllers;

use App\Mpesa\STKPush;
use App\Models\MpesaSTK;
use Iankumu\Mpesa\Facades\Mpesa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MpesaSTKPUSHController extends Controller
{
    public $result_code = 1;
    public $result_desc = 'An error occured';

    // Initiate  Stk Push Request
    public function STKPush(Request $request)
    {


        $amount = $request->input('amount');
        $phoneno = $request->input('phonenumber');
        $account_number = $request->input('account_number');
        $transactionType = $request->transaction_type == 'paybill' ? Mpesa::PAYBILL : Mpesa::TILL;


        // $callback = $request->input('callback');

        $response = Mpesa::stkpush(
            $phoneno,
            $amount,
            $account_number,
            null,
            $transactionType
        );
        // $result = json_decode((string)$response, true);

        /** @var \Illuminate\Http\Client\Response $response */
        $result = $response->json();

        if (!is_null($result)) {
            MpesaSTK::create([
                'merchant_request_id' =>  $result['MerchantRequestID'],
                'checkout_request_id' =>  $result['CheckoutRequestID']
            ]);
        }


        return Inertia::render('Payments/Partials/Stkpush', [
            'response' => $result,
        ]);
    }

    // This function is used to handle the callback response from Safaricom once a transaction is complete
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

        $response = Mpesa::stkquery($checkoutRequestId);
        $result = json_decode((string)$response);

        return Inertia::render('Payments/Partials/Stkpush', [
            'response' => $result,
        ]);
    }
}
