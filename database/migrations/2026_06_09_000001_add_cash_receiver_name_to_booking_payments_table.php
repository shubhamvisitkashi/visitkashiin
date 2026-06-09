<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCashReceiverNameToBookingPaymentsTable extends Migration
{
    public function up()
    {
        Schema::table('booking_payments', function (Blueprint $table) {
            $table->string('cash_receiver_name')->nullable()->after('payment_method');
        });
    }

    public function down()
    {
        Schema::table('booking_payments', function (Blueprint $table) {
            $table->dropColumn('cash_receiver_name');
        });
    }
}
