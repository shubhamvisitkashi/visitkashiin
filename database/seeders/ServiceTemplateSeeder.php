<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceTemplate;
use App\Models\ServiceType;

class ServiceTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get service types
        $cabType = ServiceType::where('name', 'Cab')->first();
        $boatType = ServiceType::where('name', 'Boat')->first();
        $hotelType = ServiceType::where('name', 'Hotel')->first();

        // Cab Service Templates
        if ($cabType) {
            ServiceTemplate::create([
                'service_type_id' => $cabType->id,
                'name' => 'Sedan (Dzire/Etios)',
                'description' => 'Comfortable sedan for 4 passengers with AC',
                'default_selling_price' => 1500,
                'default_cost_estimate' => 1200,
                'capacity' => 4,
                'is_active' => true,
            ]);

            ServiceTemplate::create([
                'service_type_id' => $cabType->id,
                'name' => 'Innova Crysta',
                'description' => 'Premium SUV for 6-7 passengers with AC',
                'default_selling_price' => 2500,
                'default_cost_estimate' => 2000,
                'capacity' => 7,
                'is_active' => true,
            ]);

            ServiceTemplate::create([
                'service_type_id' => $cabType->id,
                'name' => 'Tempo Traveller (12-seater)',
                'description' => 'Spacious vehicle for group travel',
                'default_selling_price' => 4000,
                'default_cost_estimate' => 3200,
                'capacity' => 12,
                'is_active' => true,
            ]);

            ServiceTemplate::create([
                'service_type_id' => $cabType->id,
                'name' => 'Tempo Traveller (17-seater)',
                'description' => 'Large vehicle for bigger groups',
                'default_selling_price' => 5000,
                'default_cost_estimate' => 4000,
                'capacity' => 17,
                'is_active' => true,
            ]);
        }

        // Boat Service Templates
        if ($boatType) {
            ServiceTemplate::create([
                'service_type_id' => $boatType->id,
                'name' => 'Small Boat (2-4 PAX)',
                'description' => 'Small boat for couples or small families',
                'default_selling_price' => 600,
                'default_cost_estimate' => 450,
                'capacity' => 4,
                'is_active' => true,
            ]);

            ServiceTemplate::create([
                'service_type_id' => $boatType->id,
                'name' => 'Medium Boat (5-8 PAX)',
                'description' => 'Medium boat for families',
                'default_selling_price' => 900,
                'default_cost_estimate' => 700,
                'capacity' => 8,
                'is_active' => true,
            ]);

            ServiceTemplate::create([
                'service_type_id' => $boatType->id,
                'name' => 'Large Boat (10-15 PAX)',
                'description' => 'Large boat for groups',
                'default_selling_price' => 1500,
                'default_cost_estimate' => 1200,
                'capacity' => 15,
                'is_active' => true,
            ]);
        }

        // Hotel Service Templates
        if ($hotelType) {
            ServiceTemplate::create([
                'service_type_id' => $hotelType->id,
                'name' => 'Standard Room',
                'description' => 'Comfortable standard room with basic amenities',
                'default_selling_price' => 1800,
                'default_cost_estimate' => 1400,
                'capacity' => 2,
                'is_active' => true,
            ]);

            ServiceTemplate::create([
                'service_type_id' => $hotelType->id,
                'name' => 'Deluxe Room',
                'description' => 'Deluxe room with premium amenities',
                'default_selling_price' => 2800,
                'default_cost_estimate' => 2200,
                'capacity' => 2,
                'is_active' => true,
            ]);

            ServiceTemplate::create([
                'service_type_id' => $hotelType->id,
                'name' => 'Suite',
                'description' => 'Luxury suite with separate living area',
                'default_selling_price' => 4500,
                'default_cost_estimate' => 3500,
                'capacity' => 4,
                'is_active' => true,
            ]);
        }
    }
}
