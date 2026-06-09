<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddB2bVendorCostToBoatBookingsTable extends Migration
{
    public function up()
    {
        Schema::table('boat_bookings', function (Blueprint $table) {
            $table->decimal('b2b_vendor_cost', 10, 2)->nullable()->default(null)->after('vendor_cost');
        });
    }

    public function down()
    {
        Schema::table('boat_bookings', function (Blueprint $table) {
            $table->dropColumn('b2b_vendor_cost');
        });
    }
}
