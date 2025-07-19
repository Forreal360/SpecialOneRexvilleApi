<?php

declare(strict_types=1);

namespace App\Services\V1;

use App\Models\ClientService;
use Illuminate\Database\Eloquent\Collection;

class ServiceService extends Service
{
    public function getClientServices(int $clientId): Collection
    {
        return ClientService::where('client_id', $clientId)->get();
    }

    public function getVehicleServices(int $clientId, int $vehicleId): Collection
    {
        return ClientService::where('client_id', $clientId)
            ->where('vehicle_id', $vehicleId)
            ->with('vehicle')
            ->get();
    }
}
