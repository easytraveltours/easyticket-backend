<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Services\FlightSearchService;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    /**
     * Agent flight search with B2B wholesale pricing and group discounts.
     */
    public function index(Request $request): JsonResponse
    {
        InventoryService::expireOverdueReservations();

        $agent = $request->user()->load('group');

        $results = FlightSearchService::search($request->all(), $agent);

        return response()->json([
            'data' => $results,
            'count' => count($results),
            'agent_group' => $agent->group?->name ?? 'Standard',
            'agent_discount' => (float) ($agent->group?->discount_percent ?? 0),
        ]);
    }
}
