<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\SendMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * List messages for this agent (inbox & sent).
     */
    public function index(Request $request): JsonResponse
    {
        $agent = $request->user();

        $messages = Message::with(['sender', 'receiver', 'booking'])
            ->where(function ($q) use ($agent) {
                $q->where('sender_id', $agent->id)
                    ->orWhere('receiver_id', $agent->id);
            })
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => MessageResource::collection($messages),
            'pagination' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'total' => $messages->total(),
            ],
        ]);
    }

    /**
     * Send message to Admin operations.
     */
    public function store(SendMessageRequest $request): JsonResponse
    {
        $agent = $request->user();
        $validated = $request->validated();

        $admin = User::where('role', 'admin')->first();

        if (! $admin) {
            return response()->json(['message' => 'Operations desk is not available.'], 500);
        }

        $message = NotificationService::sendMessage(
            sender: $agent,
            receiver: $admin,
            subject: $validated['subject'],
            body: $validated['body'],
            type: $validated['message_type'] ?? 'GENERAL',
            bookingId: $validated['booking_id'] ?? null
        );

        NotificationService::notify(
            $admin->id,
            "New Message from {$agent->company_name}",
            $validated['subject'],
            'INFO',
            '/admin/messages'
        );

        return response()->json([
            'message' => 'Message sent to operations desk.',
            'data' => new MessageResource($message->load(['sender', 'receiver'])),
        ], 201);
    }

    /**
     * Mark message as read.
     */
    public function markRead(Request $request, int $id): JsonResponse
    {
        $agent = $request->user();

        $message = Message::where('id', $id)
            ->where('receiver_id', $agent->id)
            ->firstOrFail();

        $message->update(['is_read' => true]);

        return response()->json(['message' => 'Message marked as read.']);
    }
}
