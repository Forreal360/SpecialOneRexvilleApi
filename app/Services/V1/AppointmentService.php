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

class AppointmentService extends Service
{

    public function __construct()
    {
        $this->modelClass = Appointment::class;

        // Configure searchable fields for this service
        $this->searchableFields = [
            // 'email',
            // 'description',
        ];

        // Configure pagination
        $this->per_page = 10;
    }
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

            // Validate timezone
            if (!TimezoneHelper::isValidTimezone($data['timezone'])) {
                throw new \Exception('El timezone proporcionado no es válido.');
            }

            // Convert datetime to UTC for storage
            $utcDatetime = TimezoneHelper::toUTC($data['appointment_datetime'], $data['timezone']);

            $appointment = Appointment::create([
                'client_id' => $data['client_id'],
                'vehicle_id' => $data['vehicle_id'],
                'appointment_datetime' => $utcDatetime,
                'timezone' => $data['timezone'],
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);

            $appointment->services()->attach($data['service_ids']);

            return $appointment;
        });
    }

    /**
     * Get all appointments for a client
     */
    public function getClientAppointments(int $clientId): Collection
    {
        return Appointment::with(['vehicle', 'services'])
            ->where('client_id', $clientId)
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Cancel an appointment
     */
    public function cancelAppointment(int $appointmentId, int $clientId): Appointment
    {
        return DB::transaction(function () use ($appointmentId, $clientId) {
            // Buscar la cita y verificar que pertenece al cliente
            $appointment = Appointment::where('id', $appointmentId)
                ->where('client_id', $clientId)
                ->first();

            if (!$appointment) {
                throw new \Exception('La cita no existe o no pertenece al cliente autenticado.');
            }

            // Verificar que la cita no ha sido confirmada
            if ($appointment->status == 'confirmed' || $appointment->status == 'completed' || $appointment->status == 'cancelled') {
                throw new \Exception('no se puede cancelar una cita confirmada o completada');
            }

            // Cancelar la cita
            $appointment->update(['status' => 'cancelled']);

            return $appointment->fresh(['vehicle', 'services']);
        });
    }

}
