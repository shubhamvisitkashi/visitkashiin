<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelEnquiriesTable extends Migration
{
    public function up()
    {
        Schema::create('hotel_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('category')->default('hotel');
            $table->string('hotel_name');
            $table->string('guest_name');
            $table->string('contact_number', 15);
            $table->unsignedTinyInteger('adults')->default(1);
            $table->unsignedTinyInteger('kids')->default(0);
            $table->datetime('checkin_datetime');
            $table->datetime('checkout_datetime');
            $table->unsignedSmallInteger('nights')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotel_enquiries');
    }
}
