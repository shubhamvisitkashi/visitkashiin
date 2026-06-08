<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakePhoneNullableInLeadSourcesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('lead_sources')) return;

        DB::statement('ALTER TABLE lead_sources MODIFY phone VARCHAR(20) NULL');
    }

    public function down()
    {
        if (!Schema::hasTable('lead_sources')) return;

        DB::statement('ALTER TABLE lead_sources MODIFY phone BIGINT NOT NULL');
    }
}
