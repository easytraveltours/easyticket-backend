<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ticketId = $this->route('ticket') ?? $this->route('id');

        return [
            'ticket_code' => ['sometimes', 'string', 'max:100', 'unique:tickets,ticket_code,' . $ticketId],
            'pnr' => ['sometimes', 'string', 'max:50'],
            'airline_name' => ['sometimes', 'string', 'max:100'],
            'flight_number' => ['sometimes', 'string', 'max:50'],
            'dep_airport_code' => ['sometimes', 'string', 'max:10'],
            'dep_airport_name' => ['sometimes', 'string', 'max:150'],
            'dep_city' => ['sometimes', 'string', 'max:100'],
            'dep_country' => ['sometimes', 'string', 'max:100'],
            'dep_date' => ['sometimes', 'date'],
            'dep_time' => ['sometimes', 'string', 'max:20'],
            'arr_airport_code' => ['sometimes', 'string', 'max:10'],
            'arr_airport_name' => ['sometimes', 'string', 'max:150'],
            'arr_city' => ['sometimes', 'string', 'max:100'],
            'arr_country' => ['sometimes', 'string', 'max:100'],
            'arr_date' => ['sometimes', 'date'],
            'arr_time' => ['sometimes', 'string', 'max:20'],
            'duration' => ['sometimes', 'string', 'max:50'],
            'stops' => ['sometimes', 'integer', 'min:0'],
            'cabin_class' => ['sometimes', 'string', 'max:50'],
            'baggage' => ['nullable', 'string', 'max:100'],
            'is_refundable' => ['sometimes', 'boolean'],
            'public_price' => ['sometimes', 'numeric', 'min:0'],
            'b2b_price' => ['sometimes', 'numeric', 'min:0'],
            'total_inventory' => ['sometimes', 'integer', 'min:0'],
            'available_inventory' => ['sometimes', 'integer', 'min:0'],
            'status' => ['sometimes', 'string', 'in:AVAILABLE,SOLD_OUT,CANCELLED,EXPIRED'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'expiry_date' => ['nullable', 'date'],
        ];
    }
}
