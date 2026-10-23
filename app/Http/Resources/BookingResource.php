<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_code' => $this->booking_code,
            'ticket_id' => $this->ticket_id,
            'ticket' => new TicketResource($this->whenLoaded('ticket')),
            'agent_id' => $this->agent_id,
            'agent' => new UserResource($this->whenLoaded('agent')),
            'seats_count' => (int) $this->seats_count,
            'passenger_title' => $this->passenger_title,
            'passenger_first_name' => $this->passenger_first_name,
            'passenger_last_name' => $this->passenger_last_name,
            'passenger_name' => trim(($this->passenger_title ? $this->passenger_title . ' ' : '') . $this->passenger_first_name . ' ' . $this->passenger_last_name),
            'passenger_passport' => $this->passenger_passport,
            'passenger_nationality' => $this->passenger_nationality,
            'unit_price' => (float) $this->unit_price,
            'total_amount' => (float) $this->total_amount,
            'currency' => $this->currency ?? 'PKR',
            'status' => $this->status,
            'expires_at' => $this->expires_at?->toIso8601String(),
            'payment_method' => $this->payment_method,
            'payment_tx_id' => $this->payment_tx_id,
            'payment_amount' => $this->payment_amount ? (float) $this->payment_amount : null,
            'payment_proof_url' => $this->payment_proof_url,
            'payment_proof_notes' => $this->payment_proof_notes,
            'payment_submitted_at' => $this->payment_submitted_at?->toIso8601String(),
            'verified_at' => $this->verified_at?->toIso8601String(),
            'verified_by_admin_id' => $this->verified_by_admin_id,
            'admin_notes' => $this->admin_notes,
            'activated_at' => $this->activated_at?->toIso8601String(),
            'pnr_issued' => $this->pnr_issued,
            'e_ticket_number' => $this->e_ticket_number,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
