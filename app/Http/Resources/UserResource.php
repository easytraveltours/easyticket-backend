<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'company_name' => $this->company_name,
            'travel_agency_name' => $this->travel_agency_name,
            'registration_number' => $this->registration_number,
            'business_address' => $this->business_address,
            'country' => $this->country,
            'city' => $this->city,
            'website' => $this->website,
            'expected_monthly_bookings' => $this->expected_monthly_bookings,
            'years_in_business' => $this->years_in_business,
            'documents_url' => $this->documents_url,
            'group_id' => $this->group_id,
            'group' => $this->whenLoaded('group', function () {
                return new AgentGroupResource($this->group);
            }),
            'wallet_balance' => (float) $this->wallet_balance,
            'credit_limit' => (float) $this->credit_limit,
            'last_login' => $this->last_login?->toIso8601String(),
            'last_activity' => $this->last_activity?->toIso8601String(),
            'current_page' => $this->current_page,
            'is_online' => (bool) $this->is_online,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
