<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_type_id')->constrained('service_types')->onDelete('cascade');
            $table->string('name'); // e.g., "Innova Crysta", "Sedan AC", "Boat 4-seater"
            $table->text('description')->nullable();
            $table->decimal('default_selling_price', 10, 2)->default(0); // Price shown to customer
            $table->decimal('default_cost_estimate', 10, 2)->default(0); // Estimated cost for profit calculation
            $table->integer('capacity')->nullable(); // Number of persons
            $table->string('image')->nullable(); // Optional image
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
        Schema::dropIfExists('service_templates');
    }
}
