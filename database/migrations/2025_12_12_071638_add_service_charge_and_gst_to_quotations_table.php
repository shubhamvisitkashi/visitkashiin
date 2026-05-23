<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceChargeAndGstToQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->decimal('service_charge', 12, 2)->default(0)->after('total_amount');
            $table->decimal('subtotal', 12, 2)->default(0)->after('service_charge');
            $table->enum('gst_type', ['include', 'exclude'])->default('exclude')->after('subtotal');
            $table->decimal('gst_amount', 12, 2)->default(0)->after('gst_type');
            $table->decimal('gst_percentage', 5, 2)->default(18.00)->after('gst_amount');
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
            $table->dropColumn(['service_charge', 'subtotal', 'gst_type', 'gst_amount', 'gst_percentage']);
        });
    }
}
