<?php

declare(strict_types=1);

namespace App\Actions\V1\Appointment;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\AppointmentService;
use App\Http\Resources\V1\AppointmentResource;
use App\Utilities\TimezoneHelper;
use App\Jobs\AdminNotificationJob;
use Illuminate\Support\Facades\DB;

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

        return DB::transaction(function () use ($validatedData) {
            $appointment = $this->appointmentService->createAppointment($validatedData);

            // Disparar notificación admin después de crear la cita
            $this->dispatchAdminNotification($appointment);

            return $this->successResult(
                data: new AppointmentResource($appointment),
                message: 'Cita agendada exitosamente. Se ha notificado al administrador.',
                statusCode: 201
            );
        });
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

    private function dispatchAdminNotification($appointment): void
    {
        try {
            // Cargar relaciones necesarias
            $appointment->load(['client', 'services']);
            // Obtener todos los administradores activos
            $adminIds = \App\Models\Admin::where('status', 'A')->pluck('id');

            foreach ($adminIds as $adminId) {
                // Preparar información del vehículo
                $vehicleInfo = sprintf(
                    '%s %s %s',
                    $appointment->vehicle->vehicleModel->vehicleMake->name ?? 'N/A',
                    $appointment->vehicle->vehicleModel->name ?? 'N/A',
                    $appointment->vehicle->year ?? 'N/A'
                );

                // Preparar servicios
                $serviceNames = $appointment->services->pluck('name')->join(', ');

                // Título y mensaje de la notificación
                $title = 'Nueva Cita Agendada';
                $message = 'Ingresa a la sección de citas para ver los detalles.';

                // Payload con información detallada
                $payload = [
                    'route' => "/v1/panel/appointments" // Ruta para el dashboard
                ];

                // Disparar el job de notificación admin
                AdminNotificationJob::dispatch(
                    admin_id: $adminId,
                    title: $title,
                    message: $message,
                    action: 'redirect',
                    payload: $payload,
                    fcm_tokens: [], // Se pueden agregar tokens FCM si están disponibles
                    send_push: false // Cambiar a true si quieres enviar push notifications
                );
            }
        } catch (\Exception $e) {
            // Log del error pero no fallar la creación de la cita
            \Log::error('Error dispatching admin notification for appointment: ' . $e->getMessage());
        }
    }
}
