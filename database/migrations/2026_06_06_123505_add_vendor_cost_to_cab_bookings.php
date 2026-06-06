<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVendorCostToCabBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('cab_bookings')) return;
        Schema::table('cab_bookings', function (Blueprint $table) {
            $table->decimal('vendor_cost', 10, 2)->nullable()->default(null)->after('discount');
        });
    }

    public function down()
    {
        Schema::table('cab_bookings', function (Blueprint $table) {
            $table->dropColumn('vendor_cost');
        });
    }
}
