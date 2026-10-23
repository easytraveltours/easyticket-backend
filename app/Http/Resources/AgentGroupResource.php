<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgentGroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'discount_percent' => (float) $this->discount_percent,
            'credit_limit' => (float) $this->credit_limit,
            'description' => $this->description,
            'agents_count' => $this->whenCounted('users'),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
