<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ticket_code' => $this->ticket_code,
            'pnr' => $this->pnr,
            'airline_name' => $this->airline_name,
            'flight_number' => $this->flight_number,
            'dep_airport_code' => $this->dep_airport_code,
            'dep_airport_name' => $this->dep_airport_name,
            'dep_city' => $this->dep_city,
            'dep_country' => $this->dep_country,
            'dep_date' => $this->dep_date,
            'dep_time' => $this->dep_time,
            'arr_airport_code' => $this->arr_airport_code,
            'arr_airport_name' => $this->arr_airport_name,
            'arr_city' => $this->arr_city,
            'arr_country' => $this->arr_country,
            'arr_date' => $this->arr_date,
            'arr_time' => $this->arr_time,
            'duration' => $this->duration,
            'stops' => $this->stops,
            'cabin_class' => $this->cabin_class,
            'baggage' => $this->baggage,
            'is_refundable' => (bool) $this->is_refundable,
            'ticket_rules' => $this->ticket_rules,
            'refund_rules' => $this->refund_rules,
            'change_rules' => $this->change_rules,
            'public_price' => (float) $this->public_price,
            'b2b_price' => (float) $this->b2b_price,
            'currency' => $this->currency ?? 'PKR',
            'total_inventory' => (int) $this->total_inventory,
            'available_inventory' => (int) $this->available_inventory,
            'reserved_inventory' => (int) $this->reserved_inventory,
            'active_inventory' => (int) $this->active_inventory,
            'status' => $this->status,
            'is_low_stock' => $this->isLowStock(),
            'expiry_date' => $this->expiry_date,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
