<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeroSlidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();           // uploaded filename
            $table->string('badge')->nullable();           // top badge text
            $table->string('title');                       // main headline
            $table->string('tagline')->nullable();         // subtitle
            $table->string('cta_label')->default('Explore Tours');
            $table->string('cta_url')->default('/packages');
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hero_slides');
    }
}
