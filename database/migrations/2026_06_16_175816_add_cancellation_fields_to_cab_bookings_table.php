<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCancellationFieldsToCabBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cab_bookings', function (Blueprint $table) {
            $table->text('cancel_reason')->nullable()->after('booking_status');
            $table->decimal('refund_amount', 12, 2)->nullable()->after('cancel_reason');
            $table->decimal('non_refund_amount', 12, 2)->nullable()->after('refund_amount');
            $table->timestamp('cancelled_at')->nullable()->after('non_refund_amount');
        });
    }

    public function down()
    {
        Schema::table('cab_bookings', function (Blueprint $table) {
            $table->dropColumn(['cancel_reason', 'refund_amount', 'non_refund_amount', 'cancelled_at']);
        });
    }
}
