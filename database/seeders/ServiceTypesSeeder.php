<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $serviceTypes = [
            [
                'name' => 'Cab',
                'slug' => 'cab',
                'is_active' => true,
            ],
            [
                'name' => 'Boat',
                'slug' => 'boat',
                'is_active' => true,
            ],
            [
                'name' => 'Hotel',
                'slug' => 'hotel',
                'is_active' => true,
            ],
        ];

        foreach ($serviceTypes as $serviceType) {
            ServiceType::updateOrCreate(
                ['slug' => $serviceType['slug']],
                $serviceType
            );
        }

        $this->command->info('Service types seeded successfully!');
    }
}
