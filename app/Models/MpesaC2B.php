<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MpesaC2B extends Model
{
    use HasFactory;

    protected $fillable = [
        'Transaction_type','Transaction_ID','Transaction_Time','Amount','Business_Shortcode',
        'Account_Number','Invoice_no','Organization_Account_Balance','ThirdParty_Transaction_ID',
        'Phonenumber','FirstName'
    ];
    protected $table = 'mpesa_c2b';
}
