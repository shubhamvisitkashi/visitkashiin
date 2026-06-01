<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCabExtrasToEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->unsignedTinyInteger('luggage_bags')->default(0)->after('no_of_person');
            $table->string('roof_carrier', 10)->nullable()->after('luggage_bags');
        });
    }

    public function down()
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn(['luggage_bags', 'roof_carrier']);
        });
    }
}
