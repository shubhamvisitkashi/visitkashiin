<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCabBookingLegsTable extends Migration
{
    public function up()
    {
        Schema::create('cab_booking_legs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cab_booking_id');
            $table->unsignedInteger('sequence')->default(0);
            $table->date('leg_date');
            $table->text('pickup_address')->nullable();
            $table->text('drop_address')->nullable();
            $table->decimal('fare', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('cab_booking_id')->references('id')->on('cab_bookings')->cascadeOnDelete();
            $table->index(['cab_booking_id', 'leg_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cab_booking_legs');
    }
}
