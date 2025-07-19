<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\ClientVehicle;
use App\Models\ClientService;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $client = Client::first();
        if (!$client) {
            return;
        }
        $serviceNames = [
            'Mantenimiento de Frenos',
            'Cambio de Aceite',
            'Alineación y Balanceo',
            'Cambio de Batería',
            'Revisión General',
            'Cambio de Filtro de Aire',
            'Cambio de Pastillas de Freno',
            'Rotación de Llantas',
            'Reparación de Motor',
            'Cambio de Amortiguadores',
        ];
        foreach ($client->vehicles as $vehicle) {
            $numServices = rand(0, 10);
            for ($i = 0; $i < $numServices; $i++) {
                ClientService::create([
                    'client_id' => $client->id,
                    'vehicle_id' => $vehicle->id,
                    'date' => now()->subDays(rand(0, 365 * 3))->format('Y-m-d'),
                    'name' => $serviceNames[array_rand($serviceNames)],
                ]);
            }
        }
    }
}
