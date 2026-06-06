<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPremiumFieldsToBoatBookingsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('boat_bookings')) return;
        Schema::table('boat_bookings', function (Blueprint $table) {
            $table->string('boarding_ghat')->nullable()->after('seat_number');
            $table->string('drop_ghat')->nullable()->after('boarding_ghat');
            $table->string('boat_timing')->nullable()->after('drop_ghat');
            $table->string('celebration_type')->nullable()->after('boat_timing');
            $table->boolean('decoration')->default(false)->after('celebration_type');
            $table->boolean('photographer')->default(false)->after('decoration');
            $table->boolean('live_music')->default(false)->after('photographer');
            $table->boolean('priest')->default(false)->after('live_music');
            $table->boolean('flowers')->default(false)->after('priest');
            $table->boolean('fireworks')->default(false)->after('flowers');
            $table->text('special_requests')->nullable()->after('fireworks');
            $table->string('payment_method')->nullable()->after('special_requests');
        });
    }

    public function down()
    {
        Schema::table('boat_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'boarding_ghat','drop_ghat','boat_timing','celebration_type',
                'decoration','photographer','live_music','priest','flowers',
                'fireworks','special_requests','payment_method',
            ]);
        });
    }
}
