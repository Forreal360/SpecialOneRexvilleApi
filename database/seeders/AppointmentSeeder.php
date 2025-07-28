<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\ClientVehicle;
use App\Models\VehicleService;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing data
        $clients = Client::all();
        $services = VehicleService::all();

        foreach ($clients as $client){
            $vehicles = ClientVehicle::where('client_id', $client->id)->get();
            foreach ($vehicles as $vehicle){
                $appointment = Appointment::create([
                    'client_id' => $client->id,
                    'vehicle_id' => $vehicle->id,
                    'appointment_datetime' => now()->addDays(rand(1, 30))->format('Y-m-d H:i:s'),
                    'status' => 'pending',
                    'notes' => 'This is a test appointment',
                ]);

                $appointment->services()->attach($services->random(rand(1, count($services))));
            }
        }
    }
}
