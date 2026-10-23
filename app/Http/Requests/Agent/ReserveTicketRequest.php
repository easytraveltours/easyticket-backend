<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;

class ReserveTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ticket_id' => ['required', 'exists:tickets,id'],
            'seats' => ['required', 'integer', 'min:1', 'max:9'],
            'passenger_title' => ['nullable', 'string', 'in:Mr,Mrs,Ms,Dr,Miss'],
            'passenger_first_name' => ['required', 'string', 'max:100'],
            'passenger_last_name' => ['required', 'string', 'max:100'],
            'passenger_passport' => ['required', 'string', 'max:50'],
            'passenger_nationality' => ['nullable', 'string', 'max:100'],
        ];
    }
}
