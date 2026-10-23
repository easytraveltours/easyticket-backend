<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sender_id' => $this->sender_id,
            'sender' => new UserResource($this->whenLoaded('sender')),
            'receiver_id' => $this->receiver_id,
            'receiver' => new UserResource($this->whenLoaded('receiver')),
            'booking_id' => $this->booking_id,
            'subject' => $this->subject,
            'body' => $this->body,
            'message_type' => $this->message_type,
            'is_read' => (bool) $this->is_read,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
