<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetaFieldsInSubCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->longText('meta_title')->nullable()->after('slug');
            $table->longText('meta_keyword')->nullable()->after('meta_title');
            $table->longText('meta_description')->nullable()->after('meta_keyword');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->dropColumn('meta_title');
            $table->dropColumn('meta_keyword');
            $table->dropColumn('meta_description');
        });
    }
}
