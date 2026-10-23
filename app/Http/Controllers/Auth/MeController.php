<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user()->load('group');

        $unreadNotifications = $user->notifications()->where('is_read', false)->count();
        $unreadMessages = $user->receivedMessages()->where('is_read', false)->count();

        return response()->json([
            'user' => new UserResource($user),
            'unread_notifications' => $unreadNotifications,
            'unread_messages' => $unreadMessages,
        ]);
    }
}
