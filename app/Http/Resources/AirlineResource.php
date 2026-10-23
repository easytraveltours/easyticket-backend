<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AirlineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'iata_code' => $this->iata_code,
            'icao_code' => $this->icao_code,
            'logo_url' => $this->logo_url,
            'is_active' => (bool) $this->is_active,
        ];
    }
}
