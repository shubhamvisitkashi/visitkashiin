<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveBookingIdForeignKeyFromQuotations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Drop the foreign key constraint that's causing circular dependency
            $table->dropForeign(['booking_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Restore the foreign key
            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->onDelete('set null');
        });
    }
}
