<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressLocationYputubeToProductsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('address')->nullable()->after('discounted_price');
            $table->longText('map_location')->nullable()->after('address');
            $table->longText('youtube_link')->nullable()->after('map_location');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('map_location');
            $table->dropColumn('youtube_link');
        });
    }
}
