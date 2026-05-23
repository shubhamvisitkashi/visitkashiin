<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceColumnsToLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->decimal('services_total', 10, 2)->nullable()->after('total_amount'); // Total from all services
            $table->decimal('services_cost', 10, 2)->nullable()->after('services_total'); // Total cost (vendor payments + base costs)
            $table->decimal('services_profit', 10, 2)->nullable()->after('services_cost'); // Calculated: services_total - services_cost
            
            // Index for profit queries
            $table->index('services_profit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['services_profit']);
            $table->dropColumn(['services_total', 'services_cost', 'services_profit']);
        });
    }
}
