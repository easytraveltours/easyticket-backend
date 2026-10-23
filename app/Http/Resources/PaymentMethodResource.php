<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'account_title' => $this->account_title,
            'account_number' => $this->account_number,
            'iban' => $this->iban,
            'bank_name' => $this->bank_name,
            'branch_code' => $this->branch_code,
            'instructions' => $this->instructions,
            'is_active' => (bool) $this->is_active,
            'display_order' => (int) $this->display_order,
        ];
    }
}
