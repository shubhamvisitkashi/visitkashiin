<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSortOrderToBoatTypesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('boat_types')) return;

        if (!Schema::hasColumn('boat_types', 'sort_order')) {
            Schema::table('boat_types', function (Blueprint $table) {
                $table->unsignedInteger('sort_order')->default(0)->after('is_active');
            });
        }
    }

    public function down()
    {
        if (!Schema::hasTable('boat_types')) return;

        Schema::table('boat_types', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
}
