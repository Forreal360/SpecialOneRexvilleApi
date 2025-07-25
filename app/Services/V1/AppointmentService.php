<?php

declare(strict_types=1);

namespace App\Services\V1;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\ClientVehicle;
use App\Models\VehicleService;
use App\Utilities\TimezoneHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AppointmentService
{
    /**
     * Create a new appointment
     */
    public function createAppointment(array $data): Appointment
    {
        return DB::transaction(function () use ($data) {
            // Verify that the client owns the vehicle
            $vehicle = ClientVehicle::where('id', $data['vehicle_id'])
                ->where('client_id', $data['client_id'])
                ->firstOrFail();

            // Verify that the service exists
            $service = VehicleService::findOrFail($data['service_id']);

            // Validate timezone
            if (!TimezoneHelper::isValidTimezone($data['timezone'])) {
                throw new \Exception('El timezone proporcionado no es válido.');
            }

            // Convert datetime to UTC for storage
            $utcDatetime = TimezoneHelper::toUTC($data['appointment_datetime'], $data['timezone']);

            return Appointment::create([
                'client_id' => $data['client_id'],
                'vehicle_id' => $data['vehicle_id'],
                'service_id' => $data['service_id'],
                'appointment_datetime' => $utcDatetime,
                'timezone' => $data['timezone'],
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);
        });
    }

    /**
     * Get all appointments for a client
     */
    public function getClientAppointments(int $clientId): Collection
    {
        return Appointment::with(['vehicle', 'service'])
            ->where('client_id', $clientId)
            ->orderBy('id', 'desc')
            ->get();
    }


}
