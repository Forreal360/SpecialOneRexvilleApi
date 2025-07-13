<?php

declare(strict_types=1);

namespace App\Actions\V1\Service;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\ServiceService;
use App\Http\Resources\V1\ClientServiceResource;

class ListClientServicesAction extends Action
{
    public function __construct(private ServiceService $serviceService) {}

    public function handle($data): ActionResult
    {
        $client = auth()->user();
        $services = $this->serviceService->getClientServices($client->id);
        return $this->successResult(
            data: ClientServiceResource::collection($services),
        );
    }
}
