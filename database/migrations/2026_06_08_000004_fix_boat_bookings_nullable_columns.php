<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixBoatBookingsNullableColumns extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('boat_bookings')) return;

        // email was NOT NULL but the booking form allows no email
        DB::statement('ALTER TABLE boat_bookings MODIFY email VARCHAR(255) NULL');
    }

    public function down()
    {
        if (!Schema::hasTable('boat_bookings')) return;

        DB::statement('ALTER TABLE boat_bookings MODIFY email VARCHAR(255) NOT NULL');
    }
}
