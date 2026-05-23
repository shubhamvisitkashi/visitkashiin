<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentTrackingToServiceProviders extends Migration
{
    public function up()
    {
        Schema::table('service_providers', function (Blueprint $table) {
            $table->decimal('total_bookings', 10, 2)->default(0)->after('is_active');
            $table->decimal('total_paid', 10, 2)->default(0)->after('total_bookings');
            $table->decimal('pending_amount', 10, 2)->default(0)->after('total_paid');
            $table->index('pending_amount');
        });
    }

    public function down()
    {
        Schema::table('service_providers', function (Blueprint $table) {
            $table->dropIndex(['pending_amount']);
            $table->dropColumn(['total_bookings', 'total_paid', 'pending_amount']);
        });
    }
}
