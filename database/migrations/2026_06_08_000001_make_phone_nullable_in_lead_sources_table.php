<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakePhoneNullableInLeadSourcesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('lead_sources')) return;

        Schema::table('lead_sources', function (Blueprint $table) {
            $table->bigInteger('phone')->nullable()->change();
        });
    }

    public function down()
    {
        if (!Schema::hasTable('lead_sources')) return;

        Schema::table('lead_sources', function (Blueprint $table) {
            $table->bigInteger('phone')->nullable(false)->change();
        });
    }
}
