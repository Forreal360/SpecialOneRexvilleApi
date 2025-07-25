<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Actions\V1\Appointment\CreateAppointmentAction;
use App\Actions\V1\Appointment\ListAppointmentsAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AppointmentController extends Controller
{
    public function __construct(
        private CreateAppointmentAction $createAppointmentAction,
        private ListAppointmentsAction $listAppointmentsAction
    ) {}

    /**
     * Create a new appointment
     */
    public function store(Request $request): JsonResponse
    {
        $result = $this->createAppointmentAction->execute($request->all());

        return response()->json($result->toArray(), $result->getStatusCode());
    }

    /**
     * Get all appointments for the authenticated client
     */
    public function index(Request $request): JsonResponse
    {
        $result = $this->listAppointmentsAction->execute($request->all());

        return response()->json($result->toArray(), $result->getStatusCode());
    }
}
