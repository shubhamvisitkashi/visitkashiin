<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCabBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('cab_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();

            // Customer
            $table->string('customer_name');
            $table->string('customer_phone', 20);
            $table->string('customer_alt_phone', 20)->nullable();
            $table->string('customer_email')->nullable();
            $table->string('gst_number', 20)->nullable();

            // Journey
            $table->text('pickup_address');
            $table->text('drop_address');
            $table->enum('trip_type', ['one_way', 'round_trip', 'multi_city', 'airport_transfer', 'local_rental'])->default('one_way');
            $table->date('pickup_date');
            $table->time('pickup_time');
            $table->date('return_date')->nullable();
            $table->integer('total_days')->default(1);
            $table->decimal('total_km', 8, 2)->nullable();

            // Vehicle
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->string('vehicle_name');
            $table->string('vehicle_number', 20)->nullable();
            $table->integer('seating_capacity')->nullable();

            // Pricing
            $table->decimal('base_fare', 10, 2)->default(0);
            $table->decimal('driver_allowance', 10, 2)->default(0);
            $table->decimal('toll_tax', 10, 2)->default(0);
            $table->decimal('parking', 10, 2)->default(0);
            $table->decimal('state_tax', 10, 2)->default(0);
            $table->decimal('night_charges', 10, 2)->default(0);
            $table->decimal('extra_km_charges', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('advance_paid', 10, 2)->default(0);
            $table->decimal('pending_amount', 10, 2)->default(0);

            // Status
            $table->enum('booking_status', ['pending', 'confirmed', 'assigned', 'completed', 'cancelled'])->default('confirmed');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');

            // Meta
            $table->unsignedBigInteger('lead_source_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->text('notes')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('vehicle_id')->references('id')->on('vehicles')->nullOnDelete();
            $table->foreign('lead_source_id')->references('id')->on('lead_sources')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('admins')->nullOnDelete();

            $table->index(['booking_status', 'pickup_date']);
            $table->index('customer_phone');
            $table->index('created_by');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cab_bookings');
    }
}
