<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Notification;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        InventoryService::expireOverdueReservations();

        $agent = $request->user();
        $agentId = $agent->id;

        $totalBookings = Booking::where('agent_id', $agentId)->count();
        $activeTickets = Booking::where('agent_id', $agentId)->where('status', 'ACTIVE')->count();
        $pendingPayments = Booking::where('agent_id', $agentId)->where('status', 'PAYMENT_VERIFICATION')->count();
        $activeHolds = Booking::where('agent_id', $agentId)
            ->where('status', 'RESERVED')
            ->where('expires_at', '>', now())
            ->count();

        $recentBookings = Booking::with('ticket')
            ->where('agent_id', $agentId)
            ->latest()
            ->take(6)
            ->get();

        $recentNotifications = Notification::where('user_id', $agentId)
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'metrics' => [
                'wallet_balance' => (float) $agent->wallet_balance,
                'credit_limit' => (float) $agent->credit_limit,
                'total_bookings' => $totalBookings,
                'active_tickets' => $activeTickets,
                'pending_payments' => $pendingPayments,
                'active_holds' => $activeHolds,
                'group_name' => $agent->group?->name ?? 'Standard',
                'group_discount' => (float) ($agent->group?->discount_percent ?? 0),
            ],
            'recent_bookings' => BookingResource::collection($recentBookings),
            'recent_notifications' => $recentNotifications,
        ]);
    }
}
