<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingServiceAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::create('booking_service_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('quotation_item_id')->nullable()->constrained('quotation_items')->onDelete('set null');
            $table->foreignId('service_provider_id')->constrained('service_providers')->onDelete('restrict');
            $table->foreignId('service_item_id')->constrained('service_items')->onDelete('restrict');
            $table->decimal('assigned_cost', 10, 2); // Actual vendor cost
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->date('assignment_date')->default(now());
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('booking_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_service_assignments');
    }
}
