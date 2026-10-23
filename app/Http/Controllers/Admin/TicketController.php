<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTicketRequest;
use App\Http\Requests\Admin\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Services\ActivityLogService;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * List all flight inventory with search & status filters.
     */
    public function index(Request $request): JsonResponse
    {
        InventoryService::expireOverdueReservations();

        $status = $request->input('status', 'all');
        $search = $request->input('search', '');
        $airline = $request->input('airline', '');

        $query = Ticket::query();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($airline) {
            $query->where('airline_name', 'like', "%{$airline}%");
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_code', 'like', "%{$search}%")
                    ->orWhere('flight_number', 'like', "%{$search}%")
                    ->orWhere('pnr', 'like', "%{$search}%")
                    ->orWhere('dep_city', 'like', "%{$search}%")
                    ->orWhere('arr_city', 'like', "%{$search}%");
            });
        }

        $tickets = $query->latest()->paginate(20);

        return response()->json([
            'data' => TicketResource::collection($tickets),
            'pagination' => [
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
                'total' => $tickets->total(),
            ],
        ]);
    }

    /**
     * Store a newly created flight ticket.
     */
    public function store(StoreTicketRequest $request): JsonResponse
    {
        $admin = $request->user();
        $validated = $request->validated();

        $validated['available_inventory'] = $validated['available_inventory'] ?? $validated['total_inventory'];
        $validated['reserved_inventory'] = 0;
        $validated['active_inventory'] = 0;
        $validated['status'] = 'AVAILABLE';

        $ticket = Ticket::create($validated);

        ActivityLogService::log(
            $admin,
            'TICKET_CREATED',
            'TICKET',
            $ticket->ticket_code,
            "Added flight {$ticket->airline_name} {$ticket->flight_number} ({$ticket->dep_city} -> {$ticket->arr_city})"
        );

        return response()->json([
            'message' => 'Flight ticket added to inventory successfully.',
            'data' => new TicketResource($ticket),
        ], 201);
    }

    /**
     * Show ticket details.
     */
    public function show(int $id): JsonResponse
    {
        $ticket = Ticket::with('bookings.agent')->findOrFail($id);

        return response()->json([
            'data' => new TicketResource($ticket),
            'bookings' => $ticket->bookings,
        ]);
    }

    /**
     * Update flight ticket inventory and prices.
     */
    public function update(UpdateTicketRequest $request, int $id): JsonResponse
    {
        $admin = $request->user();
        $ticket = Ticket::findOrFail($id);
        $validated = $request->validated();

        $ticket->update($validated);

        ActivityLogService::log(
            $admin,
            'TICKET_UPDATED',
            'TICKET',
            $ticket->ticket_code,
            "Updated flight inventory details for {$ticket->flight_number}"
        );

        return response()->json([
            'message' => 'Flight ticket updated successfully.',
            'data' => new TicketResource($ticket),
        ]);
    }

    /**
     * Remove ticket from inventory.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $admin = $request->user();
        $ticket = Ticket::findOrFail($id);

        // Check if has active bookings
        $hasActive = $ticket->bookings()->whereIn('status', ['ACTIVE', 'RESERVED', 'PAYMENT_VERIFICATION'])->exists();
        if ($hasActive) {
            return response()->json([
                'message' => 'Cannot delete flight ticket with active or reserved bookings. You can change status to CANCELLED instead.',
            ], 422);
        }

        $code = $ticket->ticket_code;
        $ticket->delete();

        ActivityLogService::log(
            $admin,
            'TICKET_DELETED',
            'TICKET',
            $code,
            "Deleted ticket {$code}"
        );

        return response()->json([
            'message' => "Ticket {$code} removed successfully.",
        ]);
    }
}
