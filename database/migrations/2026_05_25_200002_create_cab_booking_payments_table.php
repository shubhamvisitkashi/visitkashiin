<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCabBookingPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('cab_booking_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cab_booking_id');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->default('cash');
            $table->date('payment_date');
            $table->unsignedBigInteger('payment_account_id')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('cab_booking_id')->references('id')->on('cab_bookings')->cascadeOnDelete();
            $table->foreign('payment_account_id')->references('id')->on('payment_accounts')->nullOnDelete();
            $table->foreign('received_by')->references('id')->on('admins')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cab_booking_payments');
    }
}
