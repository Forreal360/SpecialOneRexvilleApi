<?php

declare(strict_types=1);

namespace App\Actions\V1\Service;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\VehicleService;
use App\Http\Resources\V1\VehicleServiceResource;

class ListCatalogVehicleServicesAction extends Action
{
    public function __construct(private VehicleService $vehicleService) {}

    public function handle($data): ActionResult
    {
        $services = $this->vehicleService->getCatalog();
        return $this->successResult(
            data: VehicleServiceResource::collection($services)
        );
    }
}
