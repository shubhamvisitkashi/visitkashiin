<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMobileImageToHeroSlidesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('hero_slides')) return;

        if (!Schema::hasColumn('hero_slides', 'mobile_image')) {
            Schema::table('hero_slides', function (Blueprint $table) {
                $table->string('mobile_image')->nullable()->after('image');
            });
        }
    }

    public function down()
    {
        if (!Schema::hasTable('hero_slides')) return;
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->dropColumn('mobile_image');
        });
    }
}
