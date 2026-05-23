<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('boat_type_id')->index();
            $table->foreign('boat_type_id')->references('id')->on('boat_types')->onDelete('cascade');
            $table->string('event_type');
            $table->integer('total_available_boat');
            $table->integer('no_of_seat');
            $table->decimal('price', 10, 2);
            $table->decimal('discounted_price', 10, 2);
            $table->enum('is_active', ['1', '0'])->default('1');
            $table->softDeletes();
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
        Schema::dropIfExists('boats');
    }
}
