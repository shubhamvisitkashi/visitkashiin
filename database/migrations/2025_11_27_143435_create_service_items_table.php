<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_provider_id')->constrained('service_providers')->onDelete('cascade');
            $table->foreignId('service_type_id')->constrained('service_types')->onDelete('cascade');
            $table->string('name', 200); // "Sedan", "SUV", "Deluxe Room"
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2)->default(0); // For own services
            $table->decimal('vendor_cost', 10, 2)->default(0); // For vendor services
            $table->integer('capacity')->nullable(); // PAX capacity
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index('service_provider_id');
            $table->index('service_type_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_items');
    }
}
