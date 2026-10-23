<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Services\FlightSearchService;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FlightSearchController extends Controller
{
    /**
     * Search available flights.
     */
    public function index(Request $request): JsonResponse
    {
        InventoryService::expireOverdueReservations();

        $user = $request->user('sanctum');

        $results = FlightSearchService::search($request->all(), $user);

        return response()->json([
            'data' => $results,
            'count' => count($results),
        ]);
    }

    /**
     * Get single flight detail.
     */
    public function show(Request $request, int|string $id): JsonResponse
    {
        $ticket = Ticket::where('id', $id)
            ->orWhere('ticket_code', $id)
            ->firstOrFail();

        $user = $request->user('sanctum');
        $discountPercent = 0.0;
        if ($user && ($user->isAgent() || $user->isAdmin()) && $user->group_id && $user->group) {
            $discountPercent = (float) $user->group->discount_percent;
        }

        $b2bFinal = $ticket->b2b_price;
        if ($discountPercent > 0) {
            $b2bFinal = round($b2bFinal * (1 - $discountPercent / 100));
        }

        $data = (new TicketResource($ticket))->resolve($request);
        $data['effective_price'] = ($user && ($user->isAgent() || $user->isAdmin())) ? $b2bFinal : $ticket->public_price;
        $data['discount_percent'] = $discountPercent;
        $data['final_b2b_price'] = $b2bFinal;

        return response()->json([
            'data' => $data,
        ]);
    }
}
