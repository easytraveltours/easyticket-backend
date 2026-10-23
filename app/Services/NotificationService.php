<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Send notification to a specific user.
     */
    public static function notify(
        User|int $user,
        string $title,
        string $message,
        string $type = 'INFO',
        ?string $linkUrl = null
    ): Notification {
        $userId = $user instanceof User ? $user->id : $user;

        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => strtoupper($type),
            'link_url' => $linkUrl,
            'is_read' => false,
        ]);
    }

    /**
     * Send notification to all admin users.
     */
    public static function notifyAdmins(
        string $title,
        string $message,
        string $type = 'INFO',
        ?string $linkUrl = null
    ): void {
        $adminIds = User::where('role', 'admin')->pluck('id');

        foreach ($adminIds as $adminId) {
            self::notify($adminId, $title, $message, $type, $linkUrl);
        }
    }

    /**
     * Send an internal direct message.
     */
    public static function sendMessage(
        User|int $sender,
        User|int $receiver,
        string $subject,
        string $body,
        string $type = 'GENERAL',
        ?int $bookingId = null
    ): Message {
        $senderId = $sender instanceof User ? $sender->id : $sender;
        $receiverId = $receiver instanceof User ? $receiver->id : $receiver;

        return Message::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'booking_id' => $bookingId,
            'subject' => $subject,
            'body' => $body,
            'message_type' => $type,
            'is_read' => false,
        ]);
    }
}
