<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            $user->update([
                'is_online' => false,
            ]);

            // Revoke current access token
            $user->currentAccessToken()->delete();

            ActivityLogService::log(
                $user,
                'USER_LOGOUT',
                'AUTH',
                (string) $user->id,
                'Signed out'
            );
        }

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }
}
