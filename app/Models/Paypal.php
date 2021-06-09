<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paypal extends Model
{
    protected $fillable = [
        'Transaction_id', 'status', 'payer_name', 'email', 'payer_id', 'payer_country_code', 'reference_id', 'payment_id', 'payment_status',
        'currency_code', 'amount', 'paypal_fee', 'net_amount'
    ];

    protected $table = 'paypal';
    use HasFactory;
}
