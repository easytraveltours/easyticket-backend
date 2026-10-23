<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:150'],
            'phone' => ['sometimes', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'company_name' => ['sometimes', 'string', 'max:200'],
            'business_address' => ['nullable', 'string'],
            'website' => ['nullable', 'string', 'max:200'],
            'password' => ['nullable', 'string', 'min:6'],
        ];
    }
}
