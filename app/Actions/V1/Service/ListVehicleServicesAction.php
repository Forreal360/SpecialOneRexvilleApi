<?php

declare(strict_types=1);

namespace App\Actions\V1\Service;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\ServiceService;
use App\Http\Resources\V1\VehicleServiceResource;
use App\Services\V1\VehicleService;
use App\Http\Resources\V1\ClientVehicleServiceResource;

class ListVehicleServicesAction extends Action
{
    public function __construct(
        private ServiceService $serviceService,
        private VehicleService $vehicleService
    ) {}

    public function handle($data): ActionResult
    {
        $client = auth()->user();
        $vehicleId = $data['vehicle_id'] ?? null;

        $vehicle = $this->vehicleService->findBy('id', $vehicleId);

        if (!$vehicle) {

            abort(404, trans('validation.not_found'));

        }

        $services = $this->serviceService->getVehicleServices((int) $client->id, (int) $vehicleId);

        return $this->successResult(
            data: ClientVehicleServiceResource::collection($services)
        );
    }
}
