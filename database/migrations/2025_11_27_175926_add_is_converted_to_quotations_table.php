<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsConvertedToQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->boolean('is_converted')->default(false)->after('status');
            $table->foreignId('booking_id')->nullable()->after('is_converted')->constrained('bookings')->onDelete('set null');
            
            $table->index('is_converted');
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
            $table->dropForeign(['booking_id']);
            $table->dropIndex(['is_converted']);
            $table->dropColumn(['is_converted', 'booking_id']);
        });
    }
}
