<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookingAmountToEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->decimal('booking_amount', 10, 2)->nullable()->after('package_name');
        });
    }

    public function down()
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn('booking_amount');
        });
    }
}
