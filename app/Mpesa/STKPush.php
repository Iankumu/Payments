<?php

namespace App\Mpesa;

use App\Models\MpesaSTK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// This Class is responsible for getting a response from Safaricom and Storing the Transaction Details to the Database
class STKPush
{
    public $success = true;

    public function confirm(Request $request)
    {
        $payload = json_decode($request->getContent(), true);

        Log::info('Confirmation Endpoint has been hit');

        if (isset($payload['Body']) && $payload['Body']['stkCallback']['ResultCode'] == '0') {
            $merchant_request_id = $payload['Body']['stkCallback']['MerchantRequestID'];
            $checkout_request_id = $payload['Body']['stkCallback']['CheckoutRequestID'];
            $result_desc = $payload['Body']['stkCallback']['ResultDesc'];
            $result_code = $payload['Body']['stkCallback']['ResultCode'];

            $items = collect($payload['Body']['stkCallback']['CallbackMetadata']['Item']);

            $amount = $items->firstWhere('Name', 'Amount')['Value'];
            $mpesa_receipt_number = $items->firstWhere('Name', 'MpesaReceiptNumber')['Value'];
            $transaction_date = $items->firstWhere('Name', 'TransactionDate')['Value'];
            $phonenumber = $items->firstWhere('Name', 'PhoneNumber')['Value'];

            $stkPush = MpesaSTK::where('merchant_request_id', $merchant_request_id)
                ->where('checkout_request_id', $checkout_request_id)->first();

            $data = [
                'result_desc' => $result_desc,
                'result_code' => $result_code,
                'merchant_request_id' => $merchant_request_id,
                'checkout_request_id' => $checkout_request_id,
                'amount' => $amount,
                'mpesa_receipt_number' => $mpesa_receipt_number,
                'transaction_date' => $transaction_date,
                'phonenumber' => $phonenumber,
            ];

            if ($stkPush) {
                $stkPush->fill($data)->save();
            } else {
                MpesaSTK::create($data);
            }
        } else {
            $this->success = false;
        }
        return $this->success;
    }
}
