<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations')->onDelete('cascade');
            $table->foreignId('service_template_id')->constrained('service_templates')->onDelete('restrict');
            $table->foreignId('service_type_id')->constrained('service_types')->onDelete('restrict');
            $table->integer('quantity')->default(1); // Number of persons
            $table->decimal('unit_price', 10, 2); // Price per person/unit
            $table->decimal('total_price', 12, 2); // quantity * unit_price
            $table->date('service_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('quotation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotation_items');
    }
}
