<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;

class SubmitPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_code' => ['required', 'string', 'exists:bookings,booking_code'],
            'payment_method' => ['required', 'string', 'max:100'],
            'transaction_id' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
