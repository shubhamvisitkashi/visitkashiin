<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateVehiclesTable extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->default('sedan'); // sedan, mpv, suv, tempo
            $table->integer('seating_capacity')->default(4);
            $table->string('vehicle_number')->nullable();
            $table->decimal('base_rate', 10, 2)->default(0); // rate per km
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default vehicle types
        DB::table('vehicles')->insert([
            ['name' => 'Swift Dzire',                'category' => 'sedan', 'seating_capacity' => 4,  'base_rate' => 12, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ertiga',                     'category' => 'mpv',   'seating_capacity' => 6,  'base_rate' => 14, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Innova',                     'category' => 'suv',   'seating_capacity' => 7,  'base_rate' => 16, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Innova Crysta',              'category' => 'suv',   'seating_capacity' => 7,  'base_rate' => 20, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => '12 Seater Tempo Traveller',  'category' => 'tempo', 'seating_capacity' => 12, 'base_rate' => 22, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => '17 Seater Tempo Traveller',  'category' => 'tempo', 'seating_capacity' => 17, 'base_rate' => 28, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => '20 Seater Tempo Traveller',  'category' => 'tempo', 'seating_capacity' => 20, 'base_rate' => 32, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => '26 Seater Tempo Traveller',  'category' => 'tempo', 'seating_capacity' => 26, 'base_rate' => 38, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
