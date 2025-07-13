<?php

declare(strict_types=1);

namespace App\Actions\V1\Vehicle;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\V1\VehicleResource;

class ListVehicleAction extends Action
{
    /**
     * Constructor - Inject dependencies here
     */
    public function __construct()
    {
        // Inject services here
        // Example: $this->service = $service;
    }

    /**
     * Handle the action logic
     *
     * @param array|object $data
     * @return ActionResult
     */
    public function handle($data): ActionResult
    {

        $user = auth()->user();

        $vehicles = $user->vehicles()->get();

        return $this->successResult(
            data: VehicleResource::collection($vehicles),
        );
    }
}
