<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $user = User::with('group')->where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        // For agents, check status
        if ($user->isAgent()) {
            if ($user->status === 'pending') {
                return response()->json([
                    'message' => 'Your agency application is currently pending admin review. We will notify you once approved.',
                    'status' => 'pending',
                ], 403);
            }

            if ($user->status === 'suspended') {
                return response()->json([
                    'message' => 'Your agency account has been suspended. Please contact operations support.',
                    'status' => 'suspended',
                ], 403);
            }

            if ($user->status === 'rejected') {
                return response()->json([
                    'message' => 'Your agency application was not approved. Please contact support.',
                    'status' => 'rejected',
                ], 403);
            }
        }

        // Update online status and last login
        $user->update([
            'last_login' => now(),
            'is_online' => true,
            'current_page' => '/dashboard',
        ]);

        // Create Sanctum token with ability based on role
        $token = $user->createToken(
            name: 'auth_token',
            abilities: [$user->role]
        )->plainTextToken;

        ActivityLogService::log(
            $user,
            $user->isAdmin() ? 'ADMIN_LOGIN' : 'AGENT_LOGIN',
            'AUTH',
            (string) $user->id,
            "Signed in successfully as {$user->role}"
        );

        return response()->json([
            'message' => 'Logged in successfully.',
            'token' => $token,
            'user' => new UserResource($user),
            'role' => $user->role,
        ]);
    }
}
