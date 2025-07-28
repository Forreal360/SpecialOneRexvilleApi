<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VehicleService;

class VehicleServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VehicleService::create([
            'name' => 'Oil Change',
            'status' => 'A'
        ]);

        VehicleService::create([
            'name' => 'Tire Rotation',
            'status' => 'A'
        ]);

        VehicleService::create([
            'name' => 'Brake Inspection',
            'status' => 'A'
        ]);

        VehicleService::create([
            'name' => 'Engine Tune-Up',
            'status' => 'A'
        ]);

        VehicleService::create([
            'name' => 'Transmission Service',
            'status' => 'A'
        ]);

        VehicleService::create([
            'name' => 'Coolant Flush',
            'status' => 'A'
        ]);

        VehicleService::create([
            'name' => 'Air Filter Replacement',
            'status' => 'A'
        ]);

        VehicleService::create([
            'name' => 'Fuel Filter Replacement',
            'status' => 'A'
        ]);

        VehicleService::create([
            'name' => 'Brake Pad Replacement',
            'status' => 'A'
        ]);

        VehicleService::create([
            'name' => 'Brake Rotor Replacement',
            'status' => 'A'
        ]);

        VehicleService::create([
            'name' => 'Brake Caliper Replacement',
            'status' => 'A'
        ]);

        VehicleService::create([
            'name' => 'Brake Disc Replacement',
            'status' => 'A'
        ]);

        VehicleService::create([
            'name' => 'Brake Pad Replacement',
            'status' => 'A'
        ]);

        VehicleService::create([
            'name' => 'Brake Rotor Replacement',
            'status' => 'A'
        ]);

        VehicleService::create([
            'name' => 'Brake Caliper Replacement',
            'status' => 'A'
        ]);

        VehicleService::create([
            'name' => 'Brake Pad Replacement',
            'status' => 'A'
        ]);
    }
}
