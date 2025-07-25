<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Utilities\TimezoneHelper;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'vehicle_id' => $this->vehicle_id,
            'service_id' => $this->service_id,
            'appointment_datetime' => TimezoneHelper::fromUTC($this->appointment_datetime->format('Y-m-d H:i:s'), $this->timezone),
            'timezone' => $this->timezone,
            'status' => $this->status,
            'notes' => $this->notes,
            'vehicle' => [
                'id' => $this->vehicle->id,
                'make' => $this->vehicle->make->name,
                'model' => $this->vehicle->model->name,
                'year' => $this->vehicle->year,
                'license_plate' => $this->vehicle->license_plate,
                'vin' => $this->vehicle->vin,
            ],
            'service' => [
                'id' => $this->service->id,
                'name' => $this->service->name,
            ],
        ];
    }
}
