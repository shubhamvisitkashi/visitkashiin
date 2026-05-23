<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
            $table->foreignId('service_item_id')->constrained('service_items')->onDelete('cascade');
            $table->foreignId('service_type_id')->constrained('service_types')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('selling_price', 10, 2)->default(0); // Price charged to customer
            $table->decimal('cost_price', 10, 2)->default(0); // Vendor cost or base price
            $table->decimal('profit_amount', 10, 2)->default(0); // Calculated: selling_price - cost_price
            $table->decimal('profit_percentage', 5, 2)->default(0);
            $table->date('service_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('lead_id');
            $table->index('service_item_id');
            $table->index('service_type_id');
            $table->index('service_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_services');
    }
}
