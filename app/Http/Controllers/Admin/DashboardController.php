<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityLogResource;
use App\Http\Resources\UserResource;
use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\User;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        InventoryService::expireOverdueReservations();

        $activeAgents = User::where('role', 'agent')->where('status', 'active')->count();
        $pendingAgents = User::where('role', 'agent')->where('status', 'pending')->count();

        $activeBookings = Booking::where('status', 'ACTIVE')->count();
        $pendingVerification = Booking::where('status', 'PAYMENT_VERIFICATION')->count();
        $reservedHolds = Booking::where('status', 'RESERVED')->where('expires_at', '>', now())->count();

        $totalRevenue = (float) Booking::where('status', 'ACTIVE')->sum('total_amount');
        $todayRevenue = (float) Booking::where('status', 'ACTIVE')->whereDate('activated_at', today())->sum('total_amount');

        $totalInventorySeats = (int) Ticket::sum('available_inventory');
        $lowStockFlights = Ticket::whereColumn('available_inventory', '<=', 'low_stock_threshold')->count();

        $onlineAgentsCount = User::where('role', 'agent')
            ->where(function ($q) {
                $q->where('is_online', true)
                    ->orWhere('last_activity', '>=', now()->subMinutes(15));
            })->count();

        $recentLogs = ActivityLog::latest()->take(10)->get();

        $onlineAgents = User::with('group')
            ->where('role', 'agent')
            ->where(function ($q) {
                $q->where('is_online', true)
                    ->orWhere('last_activity', '>=', now()->subMinutes(15));
            })
            ->latest('last_activity')
            ->take(8)
            ->get();

        return response()->json([
            'metrics' => [
                'active_agents' => $activeAgents,
                'pending_agents' => $pendingAgents,
                'active_bookings' => $activeBookings,
                'pending_verification' => $pendingVerification,
                'reserved_holds' => $reservedHolds,
                'total_revenue' => $totalRevenue,
                'today_revenue' => $todayRevenue,
                'available_seats' => $totalInventorySeats,
                'low_stock_flights' => $lowStockFlights,
                'online_agents' => $onlineAgentsCount,
            ],
            'recent_logs' => ActivityLogResource::collection($recentLogs),
            'online_agents_list' => UserResource::collection($onlineAgents),
        ]);
    }
}
