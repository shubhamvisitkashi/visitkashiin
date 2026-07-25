<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cab_bookings', function (Blueprint $table) {
            $table->string('driver_name', 100)->nullable()->after('vehicle_number');
            $table->string('driver_contact', 20)->nullable()->after('driver_name');
        });
    }

    public function down(): void
    {
        Schema::table('cab_bookings', function (Blueprint $table) {
            $table->dropColumn(['driver_name', 'driver_contact']);
        });
    }
};
