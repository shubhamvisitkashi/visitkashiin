<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_templates', function (Blueprint $table) {
            $table->string('property_type')->nullable()->after('address');
            $table->string('bhk_type')->nullable()->after('property_type');
        });
    }

    public function down(): void
    {
        Schema::table('service_templates', function (Blueprint $table) {
            $table->dropColumn(['property_type', 'bhk_type']);
        });
    }
};
