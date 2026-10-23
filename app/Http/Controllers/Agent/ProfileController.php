<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Get agent profile.
     */
    public function show(Request $request): JsonResponse
    {
        $agent = $request->user()->load('group');

        return response()->json([
            'data' => new UserResource($agent),
        ]);
    }

    /**
     * Update agent profile.
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $agent = $request->user();
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'] ?? $agent->name,
            'phone' => $validated['phone'] ?? $agent->phone,
            'whatsapp' => $validated['whatsapp'] ?? $agent->whatsapp,
            'company_name' => $validated['company_name'] ?? $agent->company_name,
            'business_address' => $validated['business_address'] ?? $agent->business_address,
            'website' => $validated['website'] ?? $agent->website,
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $agent->update($data);

        ActivityLogService::log(
            $agent,
            'PROFILE_UPDATED',
            'USER',
            (string) $agent->id,
            'Agency profile details updated'
        );

        return response()->json([
            'message' => 'Profile updated successfully.',
            'data' => new UserResource($agent->fresh('group')),
        ]);
    }
}
