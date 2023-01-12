<?php

namespace App\Mpesa;

use App\Models\MpesaB2C;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class B2C
{
    public function results(Request $request)
    {
        Log::info("Result URL has been hit");
        $payload = json_decode($request->getContent());

        $withdrawal = MpesaB2C::where('ConversationID', $payload->Result->ConversationID)
            ->where('OriginatorConversationID', $payload->Result->OriginatorConversationID)->first();

        if ($payload->Result->ResultCode == 0) {
            $b2cDetails = [
                'ResultType' => $payload->Result->ResultType,
                'ResultCode' => $payload->Result->ResultCode,
                'ResultDesc' => $payload->Result->ResultDesc,
                'OriginatorConversationID' => $payload->Result->OriginatorConversationID,
                'ConversationID' => $payload->Result->ConversationID,
                'TransactionID' => $payload->Result->TransactionID,
                'TransactionAmount' => $payload->Result->ResultParameters->ResultParameter[0]->Value,
                'RegisteredCustomer' => $payload->Result->ResultParameters->ResultParameter[2]->Value,
                'ReceiverPartyPublicName' => $payload->Result->ResultParameters->ResultParameter[4]->Value, //Details of Recepient
                'TransactionDateTime' => $payload->Result->ResultParameters->ResultParameter[5]->Value,
                'B2CChargesPaidAccountAvailableFunds' => $payload->Result->ResultParameters->ResultParameter[3]->Value, //Charges Paid Account Balance
                'B2CUtilityAccountAvailableFunds' => $payload->Result->ResultParameters->ResultParameter[6]->Value, //Utility Account Balance
                'B2CWorkingAccountAvailableFunds' => $payload->Result->ResultParameters->ResultParameter[7]->Value, //Working Account Balance
            ];

            $withdrawal = MpesaB2C::where('ConversationID', $payload->Result->ConversationID)
                ->where('OriginatorConversationID', $payload->Result->OriginatorConversationID)->first();

            if ($withdrawal) {
                $withdrawal->fill($b2cDetails)->save();

                return response()->json([
                    'message' => 'Withdrawal successful'
                ], Response::HTTP_OK);
            }
        }
    }
}
