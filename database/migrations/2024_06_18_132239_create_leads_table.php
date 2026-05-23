<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id')->nullable();
            $table->string('added_by')->nullable();
            $table->date('enquiry_date')->nullable();
            $table->date('booking_start_date')->nullable();
            $table->date('booking_end_date')->nullable();
            $table->string('guest_name')->nullable();
            $table->string('contact')->nullable();
            $table->string('pax')->nullable();
            $table->text('short_plan')->nullable();
            $table->longText('service_ids')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('lead_source_id')->nullable();
            $table->string('booking_status')->default('pending');
            $table->longText('plan_detail')->nullable();
            $table->longText('remark')->nullable();
            $table->string('payment_status')->nullable();
            $table->enum('is_active', ['active', 'inactivate'])->default('active');
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
        Schema::dropIfExists('leads');
    }
}
