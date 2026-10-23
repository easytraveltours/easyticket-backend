<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * List notifications.
     */
    public function index(Request $request): JsonResponse
    {
        $agent = $request->user();

        $notifications = Notification::where('user_id', $agent->id)
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => NotificationResource::collection($notifications),
            'unread_count' => Notification::where('user_id', $agent->id)->where('is_read', false)->count(),
        ]);
    }

    /**
     * Mark single notification as read.
     */
    public function markRead(Request $request, int $id): JsonResponse
    {
        $agent = $request->user();

        $notification = Notification::where('id', $id)
            ->where('user_id', $agent->id)
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return response()->json(['message' => 'Notification marked as read.']);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead(Request $request): JsonResponse
    {
        $agent = $request->user();

        Notification::where('user_id', $agent->id)->update(['is_read' => true]);

        return response()->json(['message' => 'All notifications marked as read.']);
    }
}
