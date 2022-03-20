<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpesaC2BSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mpesa_c2b', function (Blueprint $table) {
            $table->id();
            $table->string('Transaction_type')->nullable();
            $table->string('Transaction_ID')->nullable();
            $table->string('Transaction_Time')->nullable();
            $table->string('Amount')->nullable();
            $table->string('Business_Shortcode')->nullable();
            $table->string('Account_Number')->nullable();
            $table->string('Invoice_no')->nullable();
            $table->string('Organization_Account_Balance')->nullable();
            $table->string('ThirdParty_Transaction_ID')->nullable();
            $table->string('Phonenumber')->nullable();
            $table->string('FirstName')->nullable();
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
        Schema::dropIfExists('mpesa_c2b');
    }
}
