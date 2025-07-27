<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'subject' => $this->subject,
            'status' => $this->status,
            'client_id' => $this->client_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'client' => $this->whenLoaded('client', function () {
                return [
                    'id' => $this->client->id,
                    'name' => $this->client->name,
                    'last_name' => $this->client->last_name,
                    'email' => $this->client->email,
                ];
            }),
            'messages' => $this->whenLoaded('messages', function () {
                return TicketMessageResource::collection($this->messages);
            }),
            'has_new_message' => $this->new_message_from_support,
            'latest_message' => [
                'id' => $this->latestMessage->id,
                'message' => $this->latestMessage->message,
                'created_at' => $this->latestMessage->created_at->format('Y-m-d H:i:s'),
                'fromeable' => [
                    'type' => $this->latestMessage->fromeable_type,
                    'id' => $this->latestMessage->fromeable->id,
                    'name' => $this->latestMessage->fromeable->name,
                    'last_name' => $this->latestMessage->fromeable->last_name,
                    'email' => $this->latestMessage->fromeable->email,
                ],
            ],
        ];
    }
}
