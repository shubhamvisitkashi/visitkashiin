<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixBookingPaymentsReceivedByForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_payments', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['received_by']);
            
            // Change the foreign key to reference admins table instead
            $table->foreign('received_by')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_payments', function (Blueprint $table) {
            // Drop the admins foreign key
            $table->dropForeign(['received_by']);
            
            // Restore the original users foreign key
            $table->foreign('received_by')->references('id')->on('users')->onDelete('set null');
        });
    }
}
