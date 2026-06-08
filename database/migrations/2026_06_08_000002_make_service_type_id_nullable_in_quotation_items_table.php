<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakeServiceTypeIdNullableInQuotationItemsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('quotation_items')) return;

        DB::statement('ALTER TABLE quotation_items MODIFY service_type_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE quotation_items MODIFY service_template_id BIGINT UNSIGNED NULL');
    }

    public function down()
    {
        if (!Schema::hasTable('quotation_items')) return;

        DB::statement('ALTER TABLE quotation_items MODIFY service_type_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE quotation_items MODIFY service_template_id BIGINT UNSIGNED NOT NULL');
    }
}
