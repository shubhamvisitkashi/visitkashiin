<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddMultipleServiceTypesToProviders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create pivot table for provider-service type relationship
        Schema::create('service_provider_service_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_provider_id')->constrained('service_providers')->onDelete('cascade');
            $table->foreignId('service_type_id')->constrained('service_types')->onDelete('cascade');
            $table->timestamps();
            
            // Unique constraint to prevent duplicates
            $table->unique(['service_provider_id', 'service_type_id'], 'provider_type_unique');
        });

        // Migrate existing data
        $providers = DB::table('service_providers')->get();
        foreach ($providers as $provider) {
            if ($provider->service_type_id) {
                DB::table('service_provider_service_type')->insert([
                    'service_provider_id' => $provider->id,
                    'service_type_id' => $provider->service_type_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Remove old service_type_id column
        Schema::table('service_providers', function (Blueprint $table) {
            $table->dropForeign(['service_type_id']);
            $table->dropColumn('service_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Add back service_type_id column
        Schema::table('service_providers', function (Blueprint $table) {
            $table->foreignId('service_type_id')->nullable()->after('id')->constrained('service_types')->onDelete('cascade');
        });

        // Migrate data back (take first service type)
        $pivotData = DB::table('service_provider_service_type')
            ->select('service_provider_id', DB::raw('MIN(service_type_id) as service_type_id'))
            ->groupBy('service_provider_id')
            ->get();

        foreach ($pivotData as $data) {
            DB::table('service_providers')
                ->where('id', $data->service_provider_id)
                ->update(['service_type_id' => $data->service_type_id]);
        }

        // Drop pivot table
        Schema::dropIfExists('service_provider_service_type');
    }
}
