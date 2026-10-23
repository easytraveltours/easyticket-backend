<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * List all messages for operations desk.
     */
    public function index(Request $request): JsonResponse
    {
        $messages = Message::with(['sender', 'receiver', 'booking.ticket'])
            ->latest()
            ->paginate(30);

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
     * Reply or send direct message to agent.
     */
    public function store(Request $request): JsonResponse
    {
        $admin = $request->user();

        $validated = $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'subject' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string'],
            'booking_id' => ['nullable', 'exists:bookings,id'],
            'message_type' => ['nullable', 'string'],
        ]);

        $receiver = User::findOrFail($validated['receiver_id']);

        $message = NotificationService::sendMessage(
            sender: $admin,
            receiver: $receiver,
            subject: $validated['subject'],
            body: $validated['body'],
            type: $validated['message_type'] ?? 'GENERAL',
            bookingId: $validated['booking_id'] ?? null
        );

        NotificationService::notify(
            $receiver->id,
            "Message from EasyTicket Operations: {$validated['subject']}",
            $validated['subject'],
            'INFO',
            '/agent/messages'
        );

        return response()->json([
            'message' => 'Message sent to agent successfully.',
            'data' => new MessageResource($message->load(['sender', 'receiver'])),
        ], 201);
    }
}
