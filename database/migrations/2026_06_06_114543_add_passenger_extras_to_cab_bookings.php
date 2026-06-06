<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPassengerExtrasToCabBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cab_bookings', function (Blueprint $table) {
            $table->unsignedTinyInteger('no_of_adults')->default(1)->after('seating_capacity');
            $table->unsignedTinyInteger('no_of_children')->default(0)->after('no_of_adults');
            $table->boolean('carrier_on_roof')->default(false)->after('no_of_children');
            $table->boolean('child_seat')->default(false)->after('carrier_on_roof');
            $table->boolean('wheelchair_accessible')->default(false)->after('child_seat');
            $table->boolean('ac_required')->default(true)->after('wheelchair_accessible');
            $table->string('luggage_details')->nullable()->after('ac_required');
            $table->string('flight_train_number')->nullable()->after('luggage_details');
        });
    }

    public function down()
    {
        Schema::table('cab_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'no_of_adults', 'no_of_children', 'carrier_on_roof',
                'child_seat', 'wheelchair_accessible', 'ac_required',
                'luggage_details', 'flight_train_number',
            ]);
        });
    }
}
