<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientVehicleServiceResource extends JsonResource
{

    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'vehicle_id' => $this->vehicle_id,
            'service_id' => $this->service_id,
            'date' => $this->date,
            'name' => $this->service->name,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];


        return $data;
    }
}
