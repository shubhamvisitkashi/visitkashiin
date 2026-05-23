<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoatBookingPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boat_booking_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('boat_booking_id')->index();
            $table->foreign('boat_booking_id')->references('id')->on('boat_bookings')->onDelete('cascade');
            $table->double('amount', 8, 2);
            $table->string('payment_details')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('boat_booking_payments');
    }
}
