<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class OnlineAgentController extends Controller
{
    /**
     * Get real-time tracked agents with active heartbeat / online status.
     */
    public function __invoke(): JsonResponse
    {
        $onlineAgents = User::with('group')
            ->where('role', 'agent')
            ->where(function ($q) {
                $q->where('is_online', true)
                    ->orWhere('last_activity', '>=', now()->subMinutes(15));
            })
            ->orderBy('last_activity', 'desc')
            ->get();

        return response()->json([
            'data' => UserResource::collection($onlineAgents),
            'count' => $onlineAgents->count(),
        ]);
    }
}
