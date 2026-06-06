<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricingFieldsToBoatsTable extends Migration
{
    public function up()
    {
        Schema::table('boats', function (Blueprint $table) {
            $table->integer('base_pax')->default(0)->after('no_of_seat');
            $table->decimal('extra_per_person_rate', 10, 2)->default(0)->after('base_pax');
            $table->integer('max_capacity')->default(0)->after('extra_per_person_rate');
        });
    }

    public function down()
    {
        Schema::table('boats', function (Blueprint $table) {
            $table->dropColumn(['base_pax', 'extra_per_person_rate', 'max_capacity']);
        });
    }
}
