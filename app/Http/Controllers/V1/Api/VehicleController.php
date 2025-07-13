<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Actions\V1\Vehicle\ListVehicleAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class VehicleController extends Controller
{

    public function index(ListVehicleAction $action): JsonResponse
    {
        $result = $action->execute([]);
        return $result->toApiResponse();
    }

}
