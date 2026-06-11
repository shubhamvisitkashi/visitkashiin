<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cab_bookings', function (Blueprint $table) {
            $table->unsignedInteger('vehicle_count')->default(1)->after('seating_capacity');
        });
    }

    public function down(): void
    {
        Schema::table('cab_bookings', function (Blueprint $table) {
            $table->dropColumn('vehicle_count');
        });
    }
};
