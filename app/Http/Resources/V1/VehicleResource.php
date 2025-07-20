<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'year' => $this->year,
            'model' => $this->model->name,
            'make' => $this->make->name,
            'vin' => $this->vin,
            'buy_date' => $this->buy_date,
            'insurance' => $this->insurance,
        ];
    }
}
