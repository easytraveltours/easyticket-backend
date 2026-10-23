<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Record an audit log entry.
     */
    public static function log(
        User|int|null $user,
        string $action,
        ?string $objectType = null,
        ?string $objectId = null,
        ?string $details = null,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): ActivityLog {
        $userId = null;
        $userName = null;
        $userRole = null;

        if ($user instanceof User) {
            $userId = $user->id;
            $userName = $user->name;
            $userRole = $user->role;
        } elseif (is_int($user)) {
            $userId = $user;
            $foundUser = User::find($user);
            if ($foundUser) {
                $userName = $foundUser->name;
                $userRole = $foundUser->role;
            }
        } elseif ($action === 'SYSTEM' || $user === null) {
            $userName = 'System';
            $userRole = 'system';
        }

        return ActivityLog::create([
            'user_id' => $userId,
            'user_name' => $userName,
            'user_role' => $userRole,
            'action' => $action,
            'object_type' => $objectType,
            'object_id' => $objectId ? (string) $objectId : null,
            'details' => $details,
            'ip_address' => $ipAddress ?? Request::ip() ?? '127.0.0.1',
            'user_agent' => $userAgent ?? Request::userAgent() ?? 'System',
        ]);
    }
}
