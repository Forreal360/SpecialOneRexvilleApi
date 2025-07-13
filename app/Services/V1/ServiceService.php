<?php

declare(strict_types=1);

namespace App\Services\V1;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;

class ServiceService extends Service
{
    public function getClientServices(int $clientId): Collection
    {
        return Service::where('client_id', $clientId)->get();
    }

    public function getVehicleServices(int $clientId, int $vehicleId): Collection
    {
        return Service::where('client_id', $clientId)
            ->where('vehicle_id', $vehicleId)
            ->with('vehicle')
            ->get();
    }
}
