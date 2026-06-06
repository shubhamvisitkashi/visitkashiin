<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeatNumberToBoatBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('boat_bookings')) return;
        Schema::table('boat_bookings', function (Blueprint $table) {
            $table->string('seat_number')->nullable()->after('booking_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('boat_bookings', function (Blueprint $table) {
            $table->dropColumn('seat_number');
        });
    }
}
