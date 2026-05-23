<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoatBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boat_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id')->unique()->index();
            $table->unsignedBigInteger('booked_by')->index();
            $table->foreign('booked_by')->references('id')->on('admins')->onDelete('cascade');
            $table->unsignedBigInteger('boat_id')->index();
            $table->foreign('boat_id')->references('id')->on('boats')->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->integer('no_of_person');
            $table->double('total_amount', 15, 2);
            $table->double('total_discount', 15, 2);
            $table->double('final_amount', 15, 2);
            $table->dateTime('booking_date');
            $table->string('booking_status');
            $table->string('payment_status');
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
        Schema::dropIfExists('boat_bookings');
    }
}
