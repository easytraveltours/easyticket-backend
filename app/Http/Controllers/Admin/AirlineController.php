<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AirlineResource;
use App\Models\Airline;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AirlineController extends Controller
{
    /**
     * List airlines.
     */
    public function index(): JsonResponse
    {
        $airlines = Airline::orderBy('name')->get();

        return response()->json([
            'data' => AirlineResource::collection($airlines),
        ]);
    }

    /**
     * Store airline.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'iata_code' => ['required', 'string', 'max:10', 'unique:airlines,iata_code'],
            'icao_code' => ['nullable', 'string', 'max:10'],
            'logo_url' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $airline = Airline::create($validated);

        return response()->json([
            'message' => 'Airline added successfully.',
            'data' => new AirlineResource($airline),
        ], 201);
    }
}
