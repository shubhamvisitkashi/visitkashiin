<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVendorPaymentStatusToBookingServices extends Migration
{
    public function up()
    {
        Schema::table('booking_services', function (Blueprint $table) {
            $table->enum('vendor_payment_status', ['pending', 'partial', 'paid'])->default('pending')->after('notes');
            $table->decimal('vendor_paid_amount', 10, 2)->default(0)->after('vendor_payment_status');
            $table->index('vendor_payment_status');
        });
    }

    public function down()
    {
        Schema::table('booking_services', function (Blueprint $table) {
            $table->dropIndex(['vendor_payment_status']);
            $table->dropColumn(['vendor_payment_status', 'vendor_paid_amount']);
        });
    }
}
