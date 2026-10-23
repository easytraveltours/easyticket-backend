<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'company_name' => ['required', 'string', 'max:200'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'phone' => ['required', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'travel_agency_name' => ['nullable', 'string', 'max:200'],
            'registration_number' => ['nullable', 'string', 'max:100'],
            'business_address' => ['nullable', 'string'],
            'website' => ['nullable', 'string', 'max:200'],
            'expected_monthly_bookings' => ['nullable', 'string', 'max:50'],
            'years_in_business' => ['nullable', 'string', 'max:20'],
        ];
    }
}
