<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\ReserveTicketRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Ticket;
use App\Services\ActivityLogService;
use App\Services\InventoryService;
use App\Services\ReservationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * List active holds for the agent.
     */
    public function index(Request $request): JsonResponse
    {
        InventoryService::expireOverdueReservations();

        $agent = $request->user();

        $reservations = Booking::with('ticket')
            ->where('agent_id', $agent->id)
            ->where('status', 'RESERVED')
            ->where('expires_at', '>', now())
            ->orderBy('expires_at', 'asc')
            ->get();

        return response()->json([
            'data' => BookingResource::collection($reservations),
        ]);
    }

    /**
     * Reserve seats atomically.
     */
    public function store(ReserveTicketRequest $request): JsonResponse
    {
        $agent = $request->user()->load('group');
        $validated = $request->validated();

        try {
            $result = ReservationService::reserve(
                agent: $agent,
                ticketId: (int) $validated['ticket_id'],
                seats: (int) $validated['seats'],
                passengerData: $validated
            );

            return response()->json([
                'message' => 'Seats successfully reserved! Please submit payment verification before hold expires.',
                'data' => $result,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Cancel an active reservation.
     */
    public function cancel(Request $request, string $bookingCode): JsonResponse
    {
        $agent = $request->user();

        $booking = Booking::with('ticket')
            ->where('agent_id', $agent->id)
            ->where('booking_code', $bookingCode)
            ->where('status', 'RESERVED')
            ->firstOrFail();

        DB::transaction(function () use ($booking, $agent) {
            $booking->update(['status' => 'CANCELLED']);

            $seats = $booking->seats_count ?: 1;
            if ($booking->ticket_id) {
                Ticket::where('id', $booking->ticket_id)
                    ->update([
                        'available_inventory' => DB::raw("available_inventory + {$seats}"),
                        'reserved_inventory' => DB::raw("CASE WHEN reserved_inventory >= {$seats} THEN reserved_inventory - {$seats} ELSE 0 END"),
                    ]);
            }

            ActivityLogService::log(
                $agent,
                'RESERVATION_CANCELLED',
                'BOOKING',
                $booking->booking_code,
                "Agent cancelled hold for {$seats} seat(s)."
            );
        });

        return response()->json([
            'message' => "Reservation {$bookingCode} has been cancelled and seats released.",
        ]);
    }
}
