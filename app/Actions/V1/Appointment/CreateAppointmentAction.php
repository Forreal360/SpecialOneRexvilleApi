<?php

declare(strict_types=1);

namespace App\Actions\V1\Appointment;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\AppointmentService;
use App\Http\Resources\V1\AppointmentResource;
use App\Utilities\TimezoneHelper;

class CreateAppointmentAction extends Action
{
    public function __construct(private AppointmentService $appointmentService) {}

    public function handle($data): ActionResult
    {
        $validatedData = $this->validateData($data, [
            'vehicle_id' => 'required|integer|exists:client_vehicles,id',
            'service_ids' => 'required|array|exists:vehicle_services,id',
            'appointment_datetime' => 'required|date|after_or_equal:now',
            'notes' => 'nullable|string|max:500',
        ]);

        $validatedData['timezone'] = 'America/Puerto_Rico';

        // Add client_id from authenticated user
        $validatedData['client_id'] = auth()->user()->id;

        // Verify that the vehicle belongs to the authenticated client
        $this->validateVehicleOwnership($validatedData['vehicle_id'], $validatedData['client_id']);

        $appointment = $this->appointmentService->createAppointment($validatedData);

        return $this->successResult(
            data: new AppointmentResource($appointment),
            message: 'Cita agendada exitosamente.',
            statusCode: 201
        );
    }

    private function validateVehicleOwnership(int $vehicleId, int $clientId): void
    {
        $vehicle = \App\Models\ClientVehicle::where('id', $vehicleId)
            ->where('client_id', $clientId)
            ->first();

        if (!$vehicle) {
            throw new \Exception('El vehículo seleccionado no pertenece al cliente autenticado.');
        }
    }
}
