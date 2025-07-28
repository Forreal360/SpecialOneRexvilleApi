<?php

declare(strict_types=1);

namespace App\Actions\V1\Appointment;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\AppointmentService;
use App\Http\Resources\V1\AppointmentResource;

class ListAppointmentsAction extends Action
{
    public function __construct(private AppointmentService $appointmentService) {}

    public function handle($data): ActionResult
    {
        $client = auth()->user();
        $appointments = $this->appointmentService->getClientAppointments($client->id);
        return $this->successResult(
            data: AppointmentResource::collection($appointments),
            message: 'Citas obtenidas exitosamente.',
        );
    }
}
