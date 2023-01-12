<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mpesa_b2_c_s', function (Blueprint $table) {
            $table->id();
            $table->string('ResultType')->nullable();
            $table->string('ResultCode')->nullable();
            $table->string('ResultDesc')->nullable();
            $table->string('OriginatorConversationID')->nullable();
            $table->string('ConversationID')->nullable();
            $table->string('TransactionID')->nullable();
            $table->string('TransactionAmount')->nullable();
            $table->string('RegisteredCustomer')->nullable();
            $table->string('ReceiverPartyPublicName')->nullable();
            $table->string('B2CChargesPaidAccountAvailableFunds')->nullable();
            $table->string('B2CUtilityAccountAvailableFunds')->nullable();
            $table->string('B2CWorkingAccountAvailableFunds')->nullable();
            $table->string('TransactionDateTime')->nullable();


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
        Schema::dropIfExists('mpesa_b2_c_s');
    }
};
