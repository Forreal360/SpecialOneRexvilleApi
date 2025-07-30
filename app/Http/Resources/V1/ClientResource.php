<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'name' => $this->name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_code' => $this->phone_code,
            'phone' => $this->phone,
            'license_number' => $this->license_number,
            'profile_photo' => $this->profile_photo ?? null,
            'background_photo' => $this->vehicles->first()->image_path ?? null,
            'status' => $this->status,
            'fcm_token' => $this->currentAccessToken()->fcm_token ?? $request->fcm_token,
            'os' => $this->currentAccessToken()->os ?? $request->os,
            'social_accounts' => SocialAccountResource::collection($this->socialAccounts),
        ];
    }
}
