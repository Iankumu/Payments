<?php

namespace App\Mpesa;

use App\Models\MpesaC2B;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class C2B
{
    public function confirm(Request $request)
    {
        Log::info('Confirmation endpoint has been hit');
        $payload = $request->all();

        $c2b = new MpesaC2B();
        $c2b->Transaction_type = $payload['TransactionType'];
        $c2b->mpesa_receipt_number = $payload['TransID'];
        $c2b->transaction_date = $payload['TransTime'];
        $c2b->amount = $payload['TransAmount'];
        $c2b->Business_Shortcode = $payload['BusinessShortCode'];
        $c2b->Account_Number = $payload['BillRefNumber'];
        $c2b->Invoice_no = $payload['InvoiceNumber'];
        $c2b->Organization_Account_Balance = $payload['OrgAccountBalance'];
        $c2b->ThirdParty_Transaction_ID = $payload['ThirdPartyTransID'];
        $c2b->phonenumber = $payload['MSISDN'];
        $c2b->FirstName = $payload['FirstName'];
        $c2b->save();

        return $payload;
    }
}
