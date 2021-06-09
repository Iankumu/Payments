<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaypalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypal', function (Blueprint $table) {
            $table->id();
            $table->string('Transaction_id');
            $table->string('status');
            $table->string('payer_name');
            $table->string('email');
            $table->string('payer_id');
            $table->string('payer_country_code');
            $table->string('reference_id');
            $table->string('payment_id');
            $table->string('payment_status');
            $table->string('currency_code');
            $table->string('amount');
            $table->string('paypal_fee');
            $table->string('net_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paypal');
    }
}
