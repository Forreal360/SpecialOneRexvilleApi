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
    }
}
