<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AirportResource;
use App\Http\Resources\CountryResource;
use App\Models\Airport;
use App\Models\Country;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    /**
     * List countries.
     */
    public function countries(): JsonResponse
    {
        $countries = Country::orderBy('name')->get();

        return response()->json([
            'data' => CountryResource::collection($countries),
        ]);
    }

    /**
     * Store country.
     */
    public function storeCountry(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:countries,name'],
            'code' => ['required', 'string', 'max:10', 'unique:countries,code'],
            'iso3' => ['nullable', 'string', 'max:10'],
            'phonecode' => ['nullable', 'string', 'max:10'],
        ]);

        $country = Country::create($validated);

        return response()->json([
            'message' => 'Country added successfully.',
            'data' => new CountryResource($country),
        ], 201);
    }

    /**
     * List airports.
     */
    public function airports(Request $request): JsonResponse
    {
        $query = Airport::query();
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%");
            });
        }

        $airports = $query->orderBy('name')->paginate(30);

        return response()->json([
            'data' => AirportResource::collection($airports),
            'pagination' => [
                'current_page' => $airports->currentPage(),
                'last_page' => $airports->lastPage(),
                'total' => $airports->total(),
            ],
        ]);
    }

    /**
     * Store airport.
     */
    public function storeAirport(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:10', 'unique:airports,code'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'timezone' => ['nullable', 'string', 'max:50'],
        ]);

        $airport = Airport::create($validated);

        return response()->json([
            'message' => 'Airport added successfully.',
            'data' => new AirportResource($airport),
        ], 201);
    }

    /**
     * Default fallback featured destinations matching the homepage design.
     */
    public static function defaultFeaturedDestinations(): array
    {
        return [
            [
                'id' => 'dubai',
                'city' => 'Dubai',
                'country' => 'United Arab Emirates',
                'airport' => 'DXB',
                'origin' => 'ISB',
                'tag' => 'Best Seller',
                'flights' => 'Daily 12+ Flights',
                'description' => 'Futuristic skyline, luxury beach resorts, world-class shopping & premier global business hub.',
                'price' => 68500,
                'image' => '/images/destinations/dubai.jpg',
                'category' => 'gulf',
            ],
            [
                'id' => 'jeddah',
                'city' => 'Jeddah / Makkah',
                'country' => 'Saudi Arabia',
                'airport' => 'JED',
                'origin' => 'LHE',
                'tag' => 'Umrah & Hajj',
                'flights' => 'Daily 8+ Flights',
                'description' => 'Sacred gateway for Umrah & Hajj pilgrims with non-stop direct connectivity year-round.',
                'price' => 89000,
                'image' => '/images/destinations/jeddah.jpg',
                'category' => 'pilgrimage',
            ],
            [
                'id' => 'london',
                'city' => 'London',
                'country' => 'United Kingdom',
                'airport' => 'LHR',
                'origin' => 'KHI',
                'tag' => 'Corporate Trunk',
                'flights' => 'Daily 5+ Flights',
                'description' => 'Global capital of commerce, arts, prestigious universities & prime European transit.',
                'price' => 145000,
                'image' => '/images/destinations/london.jpg',
                'category' => 'europe',
            ],
            [
                'id' => 'istanbul',
                'city' => 'Istanbul',
                'country' => 'Turkey',
                'airport' => 'IST',
                'origin' => 'ISB',
                'tag' => 'Tourism & Transit',
                'flights' => 'Daily 4+ Flights',
                'description' => 'Where continents converge — historic Bosphorus straits, Ottoman heritage & culture.',
                'price' => 98000,
                'image' => '/images/destinations/istanbul.jpg',
                'category' => 'europe',
            ],
        ];
    }

    /**
     * Helper to get featured destinations list.
     */
    public static function getFeaturedList(): array
    {
        $raw = SiteSetting::get('featured_destinations');
        if ($raw) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded) && count($decoded) > 0) {
                return $decoded;
            }
        }
        return self::defaultFeaturedDestinations();
    }

    /**
     * List all featured homepage destinations.
     */
    public function featuredDestinations(): JsonResponse
    {
        return response()->json([
            'data' => self::getFeaturedList(),
        ]);
    }

    /**
     * Add a new featured destination.
     */
    public function storeFeaturedDestination(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'airport' => ['required', 'string', 'max:10'],
            'tag' => ['nullable', 'string', 'max:50'],
            'flights' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:300'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'string'],
        ]);

        $list = self::getFeaturedList();
        $newId = Str::slug($validated['city']) . '-' . Str::random(4);

        $newItem = [
            'id' => $newId,
            'city' => $validated['city'],
            'country' => $validated['country'],
            'airport' => strtoupper($validated['airport']),
            'origin' => 'ISB',
            'tag' => $validated['tag'] ?: 'Featured',
            'flights' => $validated['flights'] ?: 'Daily Direct Flights',
            'description' => $validated['description'] ?: 'Top rated international destination with premier air connections.',
            'price' => (float) $validated['price'],
            'category' => strtolower($validated['category'] ?: 'all'),
            'image' => $validated['image'] ?: '/images/destinations/dubai.jpg',
        ];

        $list[] = $newItem;
        SiteSetting::set('featured_destinations', json_encode($list), 'Homepage featured destinations cards list');

        return response()->json([
            'message' => 'Featured destination added successfully.',
            'data' => $newItem,
            'all' => $list,
        ], 201);
    }

    /**
     * Update an existing featured destination.
     */
    public function updateFeaturedDestination(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'airport' => ['required', 'string', 'max:10'],
            'tag' => ['nullable', 'string', 'max:50'],
            'flights' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:300'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'string'],
        ]);

        $list = self::getFeaturedList();
        $found = false;

        foreach ($list as &$item) {
            if ($item['id'] === $id) {
                $item['city'] = $validated['city'];
                $item['country'] = $validated['country'];
                $item['airport'] = strtoupper($validated['airport']);
                $item['tag'] = $validated['tag'] ?: $item['tag'];
                $item['flights'] = $validated['flights'] ?: $item['flights'];
                $item['description'] = $validated['description'] ?: $item['description'];
                $item['price'] = (float) $validated['price'];
                $item['category'] = strtolower($validated['category'] ?: $item['category']);
                if (!empty($validated['image'])) {
                    $item['image'] = $validated['image'];
                }
                $found = true;
                break;
            }
        }

        if (!$found) {
            return response()->json(['message' => 'Destination not found.'], 404);
        }

        SiteSetting::set('featured_destinations', json_encode($list), 'Homepage featured destinations cards list');

        return response()->json([
            'message' => 'Featured destination updated successfully.',
            'data' => $list,
        ]);
    }

    /**
     * Delete a featured destination.
     */
    public function deleteFeaturedDestination(string $id): JsonResponse
    {
        $list = self::getFeaturedList();
        $filtered = array_values(array_filter($list, fn($item) => $item['id'] !== $id));

        SiteSetting::set('featured_destinations', json_encode($filtered), 'Homepage featured destinations cards list');

        return response()->json([
            'message' => 'Featured destination deleted successfully.',
            'data' => $filtered,
        ]);
    }

    /**
     * Reset featured destinations to default 4 cards.
     */
    public function resetFeaturedDestinations(): JsonResponse
    {
        $defaults = self::defaultFeaturedDestinations();
        SiteSetting::set('featured_destinations', json_encode($defaults), 'Homepage featured destinations cards list');

        return response()->json([
            'message' => 'Featured destinations reset to defaults.',
            'data' => $defaults,
        ]);
    }

    /**
     * Upload custom photo for a destination card.
     */
    public function uploadDestinationImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $path = $request->file('image')->store('destinations', 'public');

        return response()->json([
            'message' => 'Destination photo uploaded successfully.',
            'data' => [
                'url' => Storage::disk('public')->url($path),
            ],
        ]);
    }
}
