<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * List all bookings across the platform.
     */
    public function index(Request $request): JsonResponse
    {
        InventoryService::expireOverdueReservations();

        $status = $request->input('status', 'ALL');
        $search = $request->input('search', '');

        $query = Booking::with(['ticket', 'agent.group']);

        if ($status !== 'ALL') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhere('pnr_issued', 'like', "%{$search}%")
                    ->orWhere('e_ticket_number', 'like', "%{$search}%")
                    ->orWhere('passenger_first_name', 'like', "%{$search}%")
                    ->orWhere('passenger_last_name', 'like', "%{$search}%")
                    ->orWhere('passenger_passport', 'like', "%{$search}%")
                    ->orWhereHas('agent', function ($aq) use ($search) {
                        $aq->where('company_name', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $bookings = $query->latest()->paginate(20);

        return response()->json([
            'data' => BookingResource::collection($bookings),
            'pagination' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'total' => $bookings->total(),
            ],
        ]);
    }

    /**
     * Show single booking details.
     */
    public function show(int $id): JsonResponse
    {
        $booking = Booking::with(['ticket', 'agent.group', 'verifiedBy', 'messages.sender'])->findOrFail($id);

        return response()->json([
            'data' => new BookingResource($booking),
        ]);
    }
}
