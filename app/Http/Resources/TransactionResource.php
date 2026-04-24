<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'type' => $this->type,
            'amount' => (float) $this->amount,
            'balance_before' => (float) $this->balance_before,
            'balance_after' => (float) $this->balance_after,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'notes' => $this->notes,
            'paid_at' => $this->paid_at?->toISOString(),
            'created_at' => $this->created_at->toISOString(),
            'user' => new UserResource($this->whenLoaded('user')),
            'rfid_card' => new RfidCardResource($this->whenLoaded('rfidCard')),
        ];
    }
}
