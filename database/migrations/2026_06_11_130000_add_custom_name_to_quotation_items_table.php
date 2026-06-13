<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomNameToQuotationItemsTable extends Migration
{
    public function up()
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->string('custom_name')->nullable()->after('service_type_id');
        });
    }

    public function down()
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->dropColumn('custom_name');
        });
    }
}
