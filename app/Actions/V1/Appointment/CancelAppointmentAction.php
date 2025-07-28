<?php

declare(strict_types=1);

namespace App\Actions\V1\Appointment;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\AppointmentService;
use App\Http\Resources\V1\AppointmentResource;

class CancelAppointmentAction extends Action
{
    public function __construct(private AppointmentService $appointmentService) {}

    public function handle($data): ActionResult
    {
        $validatedData = $this->validateData($data, [
            'appointment_id' => 'required',
        ]);

        $clientId = auth()->user()->id;
        $appointmentId = $validatedData['appointment_id'];

        $appointment = $this->appointmentService->findById($appointmentId);

        if (!$appointment) {
            return $this->errorResult(
                message: 'Not found',
                statusCode: 404
            );
        }

        try {
            $appointment = $this->appointmentService->cancelAppointment($appointmentId, $clientId);

            return $this->successResult();
        } catch (\Exception $e) {
            return $this->errorResult(
                message: $e->getMessage(),
                statusCode: 400
            );
        }
    }
} 