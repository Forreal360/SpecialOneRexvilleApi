<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this["id"],
            "client_id" => $this["client_id"],
            "title" => $this["title"],
            "message" => $this["message"],
            "payload" => $this["payload"],
            "read" => $this["read"],
            "status" => $this["status"],
            "read_at" => $this["read_at"],
            "created_at" => $this["created_at"],
            "updated_at" => $this["updated_at"],
        ];
    }
}
