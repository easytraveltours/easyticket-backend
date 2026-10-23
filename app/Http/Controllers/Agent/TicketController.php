<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * List all bookings/tickets for this agent.
     */
    public function index(Request $request): JsonResponse
    {
        InventoryService::expireOverdueReservations();

        $agent = $request->user();
        $status = $request->input('status', 'ALL');

        $query = Booking::with('ticket')
            ->where('agent_id', $agent->id);

        if ($status !== 'ALL') {
            if ($status === 'PENDING') {
                $query->whereIn('status', ['RESERVED', 'PAYMENT_VERIFICATION']);
            } else {
                $query->where('status', $status);
            }
        }

        $bookings = $query->latest()->paginate(15);

        return response()->json([
            'data' => BookingResource::collection($bookings),
            'pagination' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
            ],
        ]);
    }

    /**
     * Get single ticket / booking details (for e-ticket generation).
     */
    public function show(Request $request, string $bookingCode): JsonResponse
    {
        $agent = $request->user();

        $booking = Booking::with('ticket')
            ->where('agent_id', $agent->id)
            ->where(function ($q) use ($bookingCode) {
                $q->where('booking_code', $bookingCode)
                    ->orWhere('id', $bookingCode);
            })
            ->firstOrFail();

        return response()->json([
            'data' => new BookingResource($booking),
        ]);
    }
}
