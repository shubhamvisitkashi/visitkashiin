<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGstFieldsToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->boolean('is_gst_invoice')->default(false)->after('booking_status');
            $table->string('gst_invoice_number')->nullable()->after('is_gst_invoice');
            $table->decimal('gst_rate', 5, 2)->nullable()->after('gst_invoice_number')->comment('5.00 or 12.00');
            $table->decimal('taxable_amount', 10, 2)->nullable()->after('gst_rate');
            $table->decimal('gst_amount', 10, 2)->nullable()->after('taxable_amount');
            $table->string('customer_gstin', 15)->nullable()->after('gst_amount');
            
            $table->index('gst_invoice_number');
            $table->index('is_gst_invoice');
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
            $table->dropIndex(['gst_invoice_number']);
            $table->dropIndex(['is_gst_invoice']);
            
            $table->dropColumn([
                'is_gst_invoice',
                'gst_invoice_number',
                'gst_rate',
                'taxable_amount',
                'gst_amount',
                'customer_gstin'
            ]);
        });
    }
}
