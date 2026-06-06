<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookingDetailFieldsToBoatBookings extends Migration
{
    public function up()
    {
        Schema::table('boat_bookings', function (Blueprint $table) {
            $table->integer('adults')->default(1)->after('no_of_person');
            $table->integer('children')->default(0)->after('adults');
            $table->string('booking_type')->nullable()->after('children');   // Morning Ride, Ganga Aarti, etc.
            $table->string('event_on_boat')->nullable()->after('booking_type'); // None, Birthday, etc.
            $table->time('pickup_time')->nullable()->after('event_on_boat');
            $table->time('drop_time')->nullable()->after('pickup_time');
            $table->text('guest_notes')->nullable()->after('drop_time');
            $table->string('lead_source_id')->nullable()->after('guest_notes');
            $table->unsignedBigInteger('boatman_id')->nullable()->after('lead_source_id');
            $table->decimal('vendor_cost', 10, 2)->default(0)->after('boatman_id');
            $table->decimal('margin_amount', 10, 2)->default(0)->after('vendor_cost');
            $table->decimal('extra_per_person_rate', 10, 2)->default(0)->after('margin_amount');
            $table->integer('base_pax')->default(0)->after('extra_per_person_rate');
            $table->string('created_by')->nullable()->after('base_pax');
        });
    }

    public function down()
    {
        Schema::table('boat_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'adults','children','booking_type','event_on_boat',
                'pickup_time','drop_time','guest_notes','lead_source_id',
                'boatman_id','vendor_cost','margin_amount',
                'extra_per_person_rate','base_pax','created_by',
            ]);
        });
    }
}
