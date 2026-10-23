<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;

class InventoryController extends Controller
{
    /**
     * Get live inventory statistics for the public landing page.
     */
    public function stats(): JsonResponse
    {
        $availableSeats = (int) Ticket::sum('available_inventory');
        $activeRoutes = Ticket::distinct()->count('arr_airport_code');
        $airlinesCount = Airline::where('is_active', true)->count() ?: count(\App\Services\FlightSearchService::GLOBAL_AIRLINES);
        $destinationsCount = Airport::count() ?: count(\App\Services\FlightSearchService::worldwideAirports());

        return response()->json([
            'available_seats' => $availableSeats,
            'active_routes' => $activeRoutes,
            'partner_airlines' => $airlinesCount,
            'global_destinations' => $destinationsCount,
            'verified_agents' => \App\Models\User::where('role', 'agent')->where('status', 'active')->count(),
        ]);
    }
}
