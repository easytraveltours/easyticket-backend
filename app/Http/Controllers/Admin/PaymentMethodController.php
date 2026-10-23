<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * List all payment accounts.
     */
    public function index(): JsonResponse
    {
        $methods = PaymentMethod::orderBy('display_order', 'asc')->get();

        return response()->json([
            'data' => PaymentMethodResource::collection($methods),
        ]);
    }

    /**
     * Create bank account.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'account_title' => ['required', 'string', 'max:150'],
            'account_number' => ['required', 'string', 'max:100'],
            'iban' => ['nullable', 'string', 'max:100'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'branch_code' => ['nullable', 'string', 'max:50'],
            'instructions' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'display_order' => ['nullable', 'integer'],
        ]);

        $method = PaymentMethod::create($validated);

        return response()->json([
            'message' => 'Corporate payment method added successfully.',
            'data' => new PaymentMethodResource($method),
        ], 201);
    }

    /**
     * Update bank account.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $method = PaymentMethod::findOrFail($id);

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:100'],
            'account_title' => ['sometimes', 'string', 'max:150'],
            'account_number' => ['sometimes', 'string', 'max:100'],
            'iban' => ['nullable', 'string', 'max:100'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'branch_code' => ['nullable', 'string', 'max:50'],
            'instructions' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'display_order' => ['nullable', 'integer'],
        ]);

        $method->update($validated);

        return response()->json([
            'message' => 'Corporate payment method updated.',
            'data' => new PaymentMethodResource($method),
        ]);
    }
}
