<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations')->onDelete('restrict');
            $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
            $table->string('booking_number')->unique(); // B-20251127001
            $table->date('booking_date');
            $table->enum('booking_status', ['confirmed', 'in_progress', 'completed', 'cancelled'])->default('confirmed');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('pending_amount', 12, 2)->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['lead_id', 'booking_status']);
            $table->index('booking_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
