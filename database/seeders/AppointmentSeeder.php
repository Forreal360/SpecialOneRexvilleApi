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
        $clients = Client::take(3)->get();
        $vehicles = ClientVehicle::take(3)->get();
        $services = VehicleService::take(3)->get();

        if ($clients->isEmpty() || $vehicles->isEmpty() || $services->isEmpty()) {
            $this->command->info('No hay suficientes datos para crear citas de prueba. Ejecuta primero los seeders de Client, ClientVehicle y VehicleService.');
            return;
        }

                $appointments = [];

        // Create appointments only if we have at least one of each required model
        if ($clients->isNotEmpty() && $vehicles->isNotEmpty() && $services->isNotEmpty()) {
            $appointments[] = [
                'client_id' => $clients->first()->id,
                'vehicle_id' => $vehicles->first()->id,
                'service_id' => $services->first()->id,
                'appointment_datetime' => now()->addDays(2)->setTime(9, 0),
                'timezone' => 'America/Mexico_City',
                'status' => 'pending',
                'notes' => 'Cita de mantenimiento regular',
            ];

            // Add more appointments if we have multiple services
            if ($services->count() > 1) {
                $appointments[] = [
                    'client_id' => $clients->first()->id,
                    'vehicle_id' => $vehicles->first()->id,
                    'service_id' => $services->get(1)->id,
                    'appointment_datetime' => now()->addDays(5)->setTime(14, 30),
                    'timezone' => 'America/Mexico_City',
                    'status' => 'confirmed',
                    'notes' => 'Cambio de aceite y filtros',
                ];
            }

            // Add appointment for second client if available
            if ($clients->count() > 1 && $vehicles->count() > 1 && $services->count() > 2) {
                $appointments[] = [
                    'client_id' => $clients->get(1)->id,
                    'vehicle_id' => $vehicles->get(1)->id,
                    'service_id' => $services->get(2)->id,
                    'appointment_datetime' => now()->addDays(1)->setTime(11, 0),
                    'timezone' => 'America/Mexico_City',
                    'status' => 'pending',
                    'notes' => 'Revisión de frenos',
                ];
            }
        }

        foreach ($appointments as $appointmentData) {
            Appointment::create($appointmentData);
        }

        $this->command->info('Citas de prueba creadas exitosamente.');
    }
}
