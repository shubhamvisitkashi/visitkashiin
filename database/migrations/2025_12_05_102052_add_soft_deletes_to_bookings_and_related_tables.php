<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToBookingsAndRelatedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add soft deletes to bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('admins')->onDelete('set null');
        });

        // Add soft deletes to booking_payments table
        Schema::table('booking_payments', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to booking_service_assignments table
        Schema::table('booking_service_assignments', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['deleted_at', 'deleted_by']);
        });

        Schema::table('booking_payments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('booking_service_assignments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
