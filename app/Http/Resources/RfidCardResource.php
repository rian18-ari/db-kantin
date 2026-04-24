<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RfidCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rfid_uid' => $this->rfid_uid,
            'status' => $this->status,
            'last_used_at' => $this->last_used_at?->toISOString(),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
