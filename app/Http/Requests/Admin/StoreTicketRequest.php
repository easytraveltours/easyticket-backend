<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ticket_code' => ['required', 'string', 'max:100', 'unique:tickets,ticket_code'],
            'pnr' => ['required', 'string', 'max:50'],
            'airline_name' => ['required', 'string', 'max:100'],
            'flight_number' => ['required', 'string', 'max:50'],
            'dep_airport_code' => ['required', 'string', 'max:10'],
            'dep_airport_name' => ['required', 'string', 'max:150'],
            'dep_city' => ['required', 'string', 'max:100'],
            'dep_country' => ['required', 'string', 'max:100'],
            'dep_date' => ['required', 'date'],
            'dep_time' => ['required', 'string', 'max:20'],
            'arr_airport_code' => ['required', 'string', 'max:10'],
            'arr_airport_name' => ['required', 'string', 'max:150'],
            'arr_city' => ['required', 'string', 'max:100'],
            'arr_country' => ['required', 'string', 'max:100'],
            'arr_date' => ['required', 'date'],
            'arr_time' => ['required', 'string', 'max:20'],
            'duration' => ['required', 'string', 'max:50'],
            'stops' => ['required', 'integer', 'min:0'],
            'cabin_class' => ['required', 'string', 'max:50'],
            'baggage' => ['nullable', 'string', 'max:100'],
            'is_refundable' => ['required', 'boolean'],
            'public_price' => ['required', 'numeric', 'min:0'],
            'b2b_price' => ['required', 'numeric', 'min:0'],
            'total_inventory' => ['required', 'integer', 'min:1'],
            'available_inventory' => ['nullable', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'expiry_date' => ['nullable', 'date'],
        ];
    }
}
