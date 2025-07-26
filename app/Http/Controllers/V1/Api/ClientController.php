<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Actions\V1\Client\GetClientAction;
use App\Actions\V1\Client\UpdateClientAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class ClientController extends Controller
{

    public function profile(GetClientAction $action): JsonResponse
    {
        $result = $action->execute([]);

        return $result->toApiResponse();
    }


    public function update(Request $request, UpdateClientAction $action): JsonResponse
    {
        $result = $action->execute($request->all());

        return $result->toApiResponse();
    }

    public function updateProfilePhoto(Request $request, UpdateClientAction $action): JsonResponse
    {
        $result = $action->execute($request->all());

        return $result->toApiResponse();
    }
}
