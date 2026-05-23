<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckinToEnquiriesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->string('no_of_person')->nullable()->after('phone');
            $table->datetime('checkin_time')->nullable()->after('arrival_date');
            $table->datetime('checkout_time')->nullable()->after('checkin_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn('no_of_person');
            $table->dropColumn('checkin_time');
            $table->dropColumn('checkout_time');
        });
    }
}
