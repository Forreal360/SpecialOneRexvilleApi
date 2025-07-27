<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketMessageResource extends JsonResource
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
            'ticket_id' => $this->ticket_id,
            'message' => $this->message,
            'message_type' => $this->message_type,
            'file_path' => $this->file_path,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'from' => [
                'type' => $this->fromeable_type,
                'id' => $this->fromeable_id,
                'name' => $this->fromeable->name,
                'last_name' => $this->fromeable->last_name,
                'email' => $this->fromeable->email,
            ],
        ];
    }
}
