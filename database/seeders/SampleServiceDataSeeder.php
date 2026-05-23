<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceType;
use App\Models\ServiceProvider;
use App\Models\ServiceItem;
use App\Models\BookingService;
use App\Models\Lead;
use Carbon\Carbon;

class SampleServiceDataSeeder extends Seeder
{
    public function run()
    {
        // Get service types
        $cabType = ServiceType::where('slug', 'cab')->first();
        $boatType = ServiceType::where('slug', 'boat')->first();
        $hotelType = ServiceType::where('slug', 'hotel')->first();

        // Create Cab Service Providers
        $cabVendor1 = ServiceProvider::create([
            'service_type_id' => $cabType->id,
            'name' => 'Kashi Cabs & Tours',
            'type' => 'vendor',
            'contact_person' => 'Rajesh Kumar',
            'contact_number' => '9876543210',
            'email' => 'kashicabs@example.com',
            'address' => 'Godowlia, Varanasi',
            'is_active' => true,
        ]);

        $cabVendor2 = ServiceProvider::create([
            'service_type_id' => $cabType->id,
            'name' => 'Varanasi Travels',
            'type' => 'vendor',
            'contact_person' => 'Amit Sharma',
            'contact_number' => '9876543211',
            'email' => 'varanasitravels@example.com',
            'address' => 'Cantonment, Varanasi',
            'is_active' => true,
        ]);

        $cabOwn = ServiceProvider::create([
            'service_type_id' => $cabType->id,
            'name' => 'Visit Kashi Own Cabs',
            'type' => 'own',
            'contact_person' => 'Internal Fleet',
            'contact_number' => '9876543212',
            'is_active' => true,
        ]);

        // Create Boat Service Providers
        $boatVendor1 = ServiceProvider::create([
            'service_type_id' => $boatType->id,
            'name' => 'Ganga Boat Services',
            'type' => 'vendor',
            'contact_person' => 'Mohan Lal',
            'contact_number' => '9876543213',
            'email' => 'gangaboats@example.com',
            'address' => 'Dashashwamedh Ghat, Varanasi',
            'is_active' => true,
        ]);

        $boatOwn = ServiceProvider::create([
            'service_type_id' => $boatType->id,
            'name' => 'Visit Kashi Own Boats',
            'type' => 'own',
            'contact_person' => 'Internal Fleet',
            'contact_number' => '9876543214',
            'is_active' => true,
        ]);

        // Create Hotel Service Providers
        $hotelVendor1 = ServiceProvider::create([
            'service_type_id' => $hotelType->id,
            'name' => 'Hotel Ganges View',
            'type' => 'vendor',
            'contact_person' => 'Suresh Gupta',
            'contact_number' => '9876543215',
            'email' => 'gangesview@example.com',
            'address' => 'Assi Ghat, Varanasi',
            'is_active' => true,
        ]);

        $hotelVendor2 = ServiceProvider::create([
            'service_type_id' => $hotelType->id,
            'name' => 'Kashi Heritage Hotel',
            'type' => 'vendor',
            'contact_person' => 'Ramesh Singh',
            'contact_number' => '9876543216',
            'email' => 'kashiheritage@example.com',
            'address' => 'Godowlia, Varanasi',
            'is_active' => true,
        ]);

        // Create Cab Service Items
        ServiceItem::create([
            'service_provider_id' => $cabVendor1->id,
            'service_type_id' => $cabType->id,
            'name' => 'Sedan (Dzire/Etios)',
            'description' => 'Comfortable sedan for city tours, 4 seater',
            'base_price' => 0,
            'vendor_cost' => 1200.00,
            'capacity' => 4,
            'is_active' => true,
        ]);

        ServiceItem::create([
            'service_provider_id' => $cabVendor1->id,
            'service_type_id' => $cabType->id,
            'name' => 'SUV (Innova/Ertiga)',
            'description' => 'Spacious SUV for family tours, 6-7 seater',
            'base_price' => 0,
            'vendor_cost' => 2000.00,
            'capacity' => 7,
            'is_active' => true,
        ]);

        ServiceItem::create([
            'service_provider_id' => $cabVendor2->id,
            'service_type_id' => $cabType->id,
            'name' => 'Tempo Traveller',
            'description' => 'Large vehicle for group tours, 12-14 seater',
            'base_price' => 0,
            'vendor_cost' => 3500.00,
            'capacity' => 14,
            'is_active' => true,
        ]);

        ServiceItem::create([
            'service_provider_id' => $cabOwn->id,
            'service_type_id' => $cabType->id,
            'name' => 'Own Sedan',
            'description' => 'Company owned sedan',
            'base_price' => 800.00,
            'vendor_cost' => 0,
            'capacity' => 4,
            'is_active' => true,
        ]);

        // Create Boat Service Items
        ServiceItem::create([
            'service_provider_id' => $boatVendor1->id,
            'service_type_id' => $boatType->id,
            'name' => 'Small Boat (2-4 PAX)',
            'description' => 'Small boat for sunrise/sunset cruise',
            'base_price' => 0,
            'vendor_cost' => 500.00,
            'capacity' => 4,
            'is_active' => true,
        ]);

        ServiceItem::create([
            'service_provider_id' => $boatVendor1->id,
            'service_type_id' => $boatType->id,
            'name' => 'Medium Boat (6-8 PAX)',
            'description' => 'Medium boat for group cruises',
            'base_price' => 0,
            'vendor_cost' => 800.00,
            'capacity' => 8,
            'is_active' => true,
        ]);

        ServiceItem::create([
            'service_provider_id' => $boatOwn->id,
            'service_type_id' => $boatType->id,
            'name' => 'Own Premium Boat',
            'description' => 'Company owned premium boat',
            'base_price' => 600.00,
            'vendor_cost' => 0,
            'capacity' => 6,
            'is_active' => true,
        ]);

        // Create Hotel Service Items
        ServiceItem::create([
            'service_provider_id' => $hotelVendor1->id,
            'service_type_id' => $hotelType->id,
            'name' => 'Standard Room',
            'description' => 'Standard AC room with breakfast',
            'base_price' => 0,
            'vendor_cost' => 1500.00,
            'capacity' => 2,
            'is_active' => true,
        ]);

        ServiceItem::create([
            'service_provider_id' => $hotelVendor1->id,
            'service_type_id' => $hotelType->id,
            'name' => 'Deluxe Room',
            'description' => 'Deluxe AC room with Ganga view',
            'base_price' => 0,
            'vendor_cost' => 2500.00,
            'capacity' => 2,
            'is_active' => true,
        ]);

        ServiceItem::create([
            'service_provider_id' => $hotelVendor2->id,
            'service_type_id' => $hotelType->id,
            'name' => 'Suite Room',
            'description' => 'Luxury suite with all amenities',
            'base_price' => 0,
            'vendor_cost' => 4000.00,
            'capacity' => 4,
            'is_active' => true,
        ]);

        $this->command->info('✓ Created 8 service providers');
        $this->command->info('✓ Created 10 service items');
        $this->command->info('Sample service data seeded successfully!');
    }
}
