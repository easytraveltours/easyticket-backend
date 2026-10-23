<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\FlightSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    /**
     * Search airports for autocomplete.
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q', '');
        $limit = (int) $request->input('limit', 12);

        $results = FlightSearchService::searchAirports($query, $limit);

        return response()->json([
            'data' => $results,
        ]);
    }
}
