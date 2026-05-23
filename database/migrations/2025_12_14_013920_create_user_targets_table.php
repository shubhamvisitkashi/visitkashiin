<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('admins')->onDelete('cascade');
            $table->integer('month'); // 1-12
            $table->integer('year'); // e.g., 2024
            $table->decimal('target_margin', 12, 2)->default(0); // Monthly margin target
            $table->decimal('achieved_margin', 12, 2)->default(0); // Achieved margin (auto-calculated)
            $table->text('notes')->nullable(); // Optional notes
            $table->foreignId('created_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamps();
            
            // Unique constraint: one target per user per month/year
            $table->unique(['user_id', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_targets');
    }
}
