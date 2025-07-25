<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Actions\V1\Service\ListClientServicesAction;
use App\Actions\V1\Service\ListVehicleServicesAction;
use App\Actions\V1\Service\ListCatalogVehicleServicesAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class ServiceController extends Controller
{

    public function index(ListClientServicesAction $action): JsonResponse
    {
        $result = $action->execute([]);
        return $result->toApiResponse();
    }


    public function byVehicle($vehicle_id, ListVehicleServicesAction $action): JsonResponse
    {
        $result = $action->execute(['vehicle_id' => $vehicle_id]);
        return $result->toApiResponse();
    }

    public function catalog(ListCatalogVehicleServicesAction $action): JsonResponse
    {
        $result = $action->execute([]);
        return $result->toApiResponse();
    }
}
