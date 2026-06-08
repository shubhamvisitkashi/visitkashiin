<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddPaymentAccountIdToBoatBookingsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('boat_bookings')) return;
        if (Schema::hasColumn('boat_bookings', 'payment_account_id')) return;

        DB::statement('ALTER TABLE boat_bookings ADD COLUMN payment_account_id BIGINT UNSIGNED NULL AFTER payment_method');
    }

    public function down()
    {
        if (!Schema::hasTable('boat_bookings')) return;
        if (!Schema::hasColumn('boat_bookings', 'payment_account_id')) return;

        DB::statement('ALTER TABLE boat_bookings DROP COLUMN payment_account_id');
    }
}
