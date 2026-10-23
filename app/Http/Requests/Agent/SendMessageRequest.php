<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string'],
            'booking_id' => ['nullable', 'exists:bookings,id'],
            'message_type' => ['nullable', 'string', 'in:GENERAL,BOOKING,SUPPORT,PAYMENT,TICKET_ACTIVATED'],
        ];
    }
}
