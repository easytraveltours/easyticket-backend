<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\AgentGroup;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Assign standard default agent group if available
        $defaultGroup = AgentGroup::where('name', 'Standard Agent')->orWhere('name', 'Standard')->first()
            ?? AgentGroup::first();

        $user = User::create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'password' => Hash::make($data['password']),
            'role' => 'agent',
            'status' => 'pending',
            'phone' => $data['phone'],
            'whatsapp' => $data['whatsapp'] ?? $data['phone'],
            'company_name' => $data['company_name'],
            'travel_agency_name' => $data['travel_agency_name'] ?? $data['company_name'],
            'registration_number' => $data['registration_number'] ?? null,
            'business_address' => $data['business_address'] ?? null,
            'country' => $data['country'] ?? 'Pakistan',
            'city' => $data['city'] ?? 'Islamabad',
            'website' => $data['website'] ?? null,
            'expected_monthly_bookings' => $data['expected_monthly_bookings'] ?? null,
            'years_in_business' => $data['years_in_business'] ?? null,
            'group_id' => $defaultGroup?->id,
            'wallet_balance' => 0.00,
            'credit_limit' => 0.00,
            'is_online' => false,
        ]);

        NotificationService::notifyAdmins(
            'New Agency Application',
            "New agent registration: {$user->company_name} ({$user->name}, {$user->email}). Verification required.",
            'INFO',
            '/admin/agents'
        );

        ActivityLogService::log(
            $user,
            'AGENT_REGISTERED',
            'USER',
            (string) $user->id,
            "Agency application submitted: {$user->company_name}"
        );

        return response()->json([
            'message' => 'Your agency application has been submitted successfully. Our operations team will review and approve your account shortly.',
            'user' => new UserResource($user),
        ], 201);
    }
}
