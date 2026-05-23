<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixServiceItemsTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Clear all data first
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('service_items')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Check if service_template_id column exists, if not add it
        if (!Schema::hasColumn('service_items', 'service_template_id')) {
            Schema::table('service_items', function (Blueprint $table) {
                $table->foreignId('service_template_id')
                    ->after('service_provider_id')
                    ->constrained('service_templates')
                    ->onDelete('cascade');
            });
        }
        
        // Drop service_type_id if it exists
        if (Schema::hasColumn('service_items', 'service_type_id')) {
            Schema::table('service_items', function (Blueprint $table) {
                $table->dropForeign(['service_type_id']);
                $table->dropColumn('service_type_id');
            });
        }
        
        // Drop name and description if they exist
        if (Schema::hasColumn('service_items', 'name')) {
            Schema::table('service_items', function (Blueprint $table) {
                $table->dropColumn(['name', 'description']);
            });
        }
        
        // Add unique constraint if it doesn't exist
        $indexExists = DB::select("SHOW INDEX FROM service_items WHERE Key_name = 'provider_template_unique'");
        if (empty($indexExists)) {
            Schema::table('service_items', function (Blueprint $table) {
                $table->unique(['service_provider_id', 'service_template_id'], 'provider_template_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop unique constraint
        Schema::table('service_items', function (Blueprint $table) {
            $table->dropUnique('provider_template_unique');
        });
        
        // Drop service_template_id
        Schema::table('service_items', function (Blueprint $table) {
            $table->dropForeign(['service_template_id']);
            $table->dropColumn('service_template_id');
        });
        
        // Restore old columns
        Schema::table('service_items', function (Blueprint $table) {
            $table->string('name', 200)->after('service_provider_id');
            $table->text('description')->nullable()->after('name');
            $table->foreignId('service_type_id')
                ->after('service_provider_id')
                ->constrained('service_types')
                ->onDelete('cascade');
        });
    }
}
