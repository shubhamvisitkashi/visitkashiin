<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeTripTypeToStringInCabBookings extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE cab_bookings MODIFY trip_type VARCHAR(100) NOT NULL DEFAULT 'one_way'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE cab_bookings MODIFY trip_type ENUM('one_way','round_trip','multi_city','airport_transfer','local_rental') NOT NULL DEFAULT 'one_way'");
    }
}
