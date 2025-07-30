<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\ClientVehicle;
use App\Models\ClientService;
use App\Models\VehicleService;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $clients = Client::all();
       
        foreach ($clients as $client) {
            // Asegurarse de que el cliente tenga vehículos
            foreach ($client->vehicles as $vehicle) {
                $numServices = rand(0, 10);
                for ($i = 0; $i < $numServices; $i++) {
                    ClientService::create([
                        'client_id' => $client->id,
                        'vehicle_id' => $vehicle->id,
                        'date' => now()->subDays(rand(0, 365 * 3))->format('Y-m-d'),
                        'service_id' => VehicleService::inRandomOrder()->first()->id,
                    ]);
                }
            }
            
        }
    }
}
