<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MpesaB2C extends Model
{
    use HasFactory;

    protected $fillable = [
        'ResultType', 'ResultCode', 'ResultDesc', 'OriginatorConversationID', 'ConversationID', 'TransactionID',
        'TransactionAmount', 'RegisteredCustomer', 'ReceiverPartyPublicName', 'TransactionDateTime', 'B2CChargesPaidAccountAvailableFunds',
        'B2CUtilityAccountAvailableFunds', 'B2CWorkingAccountAvailableFunds'
    ];

    protected $table = 'mpesa_b2_c_s';
}
