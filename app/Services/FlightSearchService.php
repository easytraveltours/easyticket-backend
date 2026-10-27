<?php

namespace App\Services;

use App\Models\Airport;
use App\Models\Ticket;
use App\Models\User;

class FlightSearchService
{
    public const GLOBAL_AIRLINES = [
        ['name' => 'Emirates Airline', 'code' => 'EK'],
        ['name' => 'Qatar Airways', 'code' => 'QR'],
        ['name' => 'Turkish Airlines', 'code' => 'TK'],
        ['name' => 'Pakistan International Airlines', 'code' => 'PK'],
        ['name' => 'Oman Air', 'code' => 'WY'],
        ['name' => 'Fly Dubai', 'code' => 'FZ'],
        ['name' => 'Etihad Airways', 'code' => 'EY'],
        ['name' => 'Air Sial', 'code' => 'PF'],
        ['name' => 'Virgin Atlantic', 'code' => 'VS'],
        ['name' => 'Thai Airways', 'code' => 'TG'],
        ['name' => 'Kuwait Airways', 'code' => 'KU'],
        ['name' => 'China Southern', 'code' => 'CZ'],
        ['name' => 'Saudi Arabian', 'code' => 'SV'],
        ['name' => 'Air Arabia', 'code' => 'G9'],
        ['name' => 'Air Malindo', 'code' => 'OD'],
        ['name' => 'British Airways', 'code' => 'BA'],
        ['name' => 'flyadeal', 'code' => 'F3'],
        ['name' => 'SalamAir', 'code' => 'OV'],
        ['name' => 'Flynas', 'code' => 'XY'],
        ['name' => 'Fly Jinnah', 'code' => '9P'],
        ['name' => 'Airblue', 'code' => 'PA'],
    ];

    /**
     * Worldwide airport directory.
     */
    public static function worldwideAirports(): array
    {
        return [
            // Pakistan
            ['code' => 'ISB', 'name' => 'Islamabad International Airport', 'city' => 'Islamabad', 'country' => 'Pakistan', 'tz' => 'Asia/Karachi'],
            ['code' => 'KHI', 'name' => 'Jinnah International Airport', 'city' => 'Karachi', 'country' => 'Pakistan', 'tz' => 'Asia/Karachi'],
            ['code' => 'LHE', 'name' => 'Allama Iqbal International Airport', 'city' => 'Lahore', 'country' => 'Pakistan', 'tz' => 'Asia/Karachi'],
            ['code' => 'PEW', 'name' => 'Bacha Khan International Airport', 'city' => 'Peshawar', 'country' => 'Pakistan', 'tz' => 'Asia/Karachi'],
            ['code' => 'MUX', 'name' => 'Multan International Airport', 'city' => 'Multan', 'country' => 'Pakistan', 'tz' => 'Asia/Karachi'],
            ['code' => 'SKT', 'name' => 'Sialkot International Airport', 'city' => 'Sialkot', 'country' => 'Pakistan', 'tz' => 'Asia/Karachi'],
            // Middle East
            ['code' => 'DXB', 'name' => 'Dubai International Airport', 'city' => 'Dubai', 'country' => 'United Arab Emirates', 'tz' => 'Asia/Dubai'],
            ['code' => 'AUH', 'name' => 'Abu Dhabi International Airport', 'city' => 'Abu Dhabi', 'country' => 'United Arab Emirates', 'tz' => 'Asia/Dubai'],
            ['code' => 'SHJ', 'name' => 'Sharjah International Airport', 'city' => 'Sharjah', 'country' => 'United Arab Emirates', 'tz' => 'Asia/Dubai'],
            ['code' => 'DOH', 'name' => 'Hamad International Airport', 'city' => 'Doha', 'country' => 'Qatar', 'tz' => 'Asia/Qatar'],
            ['code' => 'JED', 'name' => 'King Abdulaziz International Airport', 'city' => 'Jeddah', 'country' => 'Saudi Arabia', 'tz' => 'Asia/Riyadh'],
            ['code' => 'RUH', 'name' => 'King Khalid International Airport', 'city' => 'Riyadh', 'country' => 'Saudi Arabia', 'tz' => 'Asia/Riyadh'],
            ['code' => 'MED', 'name' => 'Prince Mohammad bin Abdulaziz Airport', 'city' => 'Medina', 'country' => 'Saudi Arabia', 'tz' => 'Asia/Riyadh'],
            ['code' => 'KWI', 'name' => 'Kuwait International Airport', 'city' => 'Kuwait City', 'country' => 'Kuwait', 'tz' => 'Asia/Kuwait'],
            ['code' => 'MCT', 'name' => 'Muscat International Airport', 'city' => 'Muscat', 'country' => 'Oman', 'tz' => 'Asia/Muscat'],
            ['code' => 'IKA', 'name' => 'Imam Khomeini International Airport', 'city' => 'Tehran', 'country' => 'Iran', 'tz' => 'Asia/Tehran'],
            // Turkey & Europe
            ['code' => 'IST', 'name' => 'Istanbul Airport', 'city' => 'Istanbul', 'country' => 'Turkey', 'tz' => 'Europe/Istanbul'],
            ['code' => 'SAW', 'name' => 'Sabiha Gokcen International Airport', 'city' => 'Istanbul', 'country' => 'Turkey', 'tz' => 'Europe/Istanbul'],
            ['code' => 'LHR', 'name' => 'Heathrow Airport', 'city' => 'London', 'country' => 'United Kingdom', 'tz' => 'Europe/London'],
            ['code' => 'MAN', 'name' => 'Manchester Airport', 'city' => 'Manchester', 'country' => 'United Kingdom', 'tz' => 'Europe/London'],
            ['code' => 'CDG', 'name' => 'Charles de Gaulle Airport', 'city' => 'Paris', 'country' => 'France', 'tz' => 'Europe/Paris'],
            ['code' => 'FRA', 'name' => 'Frankfurt Airport', 'city' => 'Frankfurt', 'country' => 'Germany', 'tz' => 'Europe/Berlin'],
            ['code' => 'AMS', 'name' => 'Amsterdam Schiphol Airport', 'city' => 'Amsterdam', 'country' => 'Netherlands', 'tz' => 'Europe/Amsterdam'],
            ['code' => 'MAD', 'name' => 'Adolfo Suarez Madrid-Barajas', 'city' => 'Madrid', 'country' => 'Spain', 'tz' => 'Europe/Madrid'],
            ['code' => 'FCO', 'name' => 'Leonardo da Vinci-Fiumicino Airport', 'city' => 'Rome', 'country' => 'Italy', 'tz' => 'Europe/Rome'],
            ['code' => 'ZRH', 'name' => 'Zurich Airport', 'city' => 'Zurich', 'country' => 'Switzerland', 'tz' => 'Europe/Zurich'],
            ['code' => 'ATH', 'name' => 'Athens International Airport', 'city' => 'Athens', 'country' => 'Greece', 'tz' => 'Europe/Athens'],
            // North America
            ['code' => 'JFK', 'name' => 'John F. Kennedy International Airport', 'city' => 'New York', 'country' => 'United States', 'tz' => 'America/New_York'],
            ['code' => 'IAD', 'name' => 'Washington Dulles International Airport', 'city' => 'Washington', 'country' => 'United States', 'tz' => 'America/New_York'],
            ['code' => 'ORD', 'name' => "O'Hare International Airport", 'city' => 'Chicago', 'country' => 'United States', 'tz' => 'America/Chicago'],
            ['code' => 'LAX', 'name' => 'Los Angeles International Airport', 'city' => 'Los Angeles', 'country' => 'United States', 'tz' => 'America/Los_Angeles'],
            ['code' => 'YYZ', 'name' => 'Toronto Pearson International Airport', 'city' => 'Toronto', 'country' => 'Canada', 'tz' => 'America/Toronto'],
            ['code' => 'YVR', 'name' => 'Vancouver International Airport', 'city' => 'Vancouver', 'country' => 'Canada', 'tz' => 'America/Vancouver'],
            // Asia
            ['code' => 'DEL', 'name' => 'Indira Gandhi International Airport', 'city' => 'Delhi', 'country' => 'India', 'tz' => 'Asia/Kolkata'],
            ['code' => 'BKK', 'name' => 'Suvarnabhumi Airport', 'city' => 'Bangkok', 'country' => 'Thailand', 'tz' => 'Asia/Bangkok'],
            ['code' => 'KUL', 'name' => 'Kuala Lumpur International Airport', 'city' => 'Kuala Lumpur', 'country' => 'Malaysia', 'tz' => 'Asia/Kuala_Lumpur'],
            ['code' => 'SIN', 'name' => 'Singapore Changi Airport', 'city' => 'Singapore', 'country' => 'Singapore', 'tz' => 'Asia/Singapore'],
            ['code' => 'HKG', 'name' => 'Hong Kong International Airport', 'city' => 'Hong Kong', 'country' => 'Hong Kong', 'tz' => 'Asia/Hong_Kong'],
            ['code' => 'PEK', 'name' => 'Beijing Capital International Airport', 'city' => 'Beijing', 'country' => 'China', 'tz' => 'Asia/Shanghai'],
            ['code' => 'NRT', 'name' => 'Narita International Airport', 'city' => 'Tokyo', 'country' => 'Japan', 'tz' => 'Asia/Tokyo'],
            ['code' => 'ICN', 'name' => 'Incheon International Airport', 'city' => 'Seoul', 'country' => 'South Korea', 'tz' => 'Asia/Seoul'],
            ['code' => 'DAC', 'name' => 'Hazrat Shahjalal International Airport', 'city' => 'Dhaka', 'country' => 'Bangladesh', 'tz' => 'Asia/Dhaka'],
            // Africa & Oceania
            ['code' => 'CAI', 'name' => 'Cairo International Airport', 'city' => 'Cairo', 'country' => 'Egypt', 'tz' => 'Africa/Cairo'],
            ['code' => 'JNB', 'name' => 'O. R. Tambo International Airport', 'city' => 'Johannesburg', 'country' => 'South Africa', 'tz' => 'Africa/Johannesburg'],
            ['code' => 'NBO', 'name' => 'Jomo Kenyatta International Airport', 'city' => 'Nairobi', 'country' => 'Kenya', 'tz' => 'Africa/Nairobi'],
            ['code' => 'SYD', 'name' => 'Sydney Kingsford Smith Airport', 'city' => 'Sydney', 'country' => 'Australia', 'tz' => 'Australia/Sydney'],
            ['code' => 'MEL', 'name' => 'Melbourne Airport', 'city' => 'Melbourne', 'country' => 'Australia', 'tz' => 'Australia/Melbourne'],
        ];
    }

    /**
     * Find airport matching code or name.
     */
    public static function findAirport(string $query): ?array
    {
        $q = strtolower(trim($query));
        if ($q === '') {
            return null;
        }

        // Check DB first
        $dbAirport = Airport::whereRaw('LOWER(code) = ?', [$q])
            ->orWhereRaw('LOWER(city) = ?', [$q])
            ->first();

        if ($dbAirport) {
            return [
                'code' => $dbAirport->code,
                'name' => $dbAirport->name,
                'city' => $dbAirport->city,
                'country' => $dbAirport->country,
                'tz' => $dbAirport->timezone ?? 'UTC',
            ];
        }

        $all = self::worldwideAirports();
        foreach ($all as $a) {
            if (strtolower($a['code']) === $q) {
                return $a;
            }
        }
        foreach ($all as $a) {
            if (str_contains(strtolower($a['code']), $q)) {
                return $a;
            }
        }
        foreach ($all as $a) {
            if (str_contains(strtolower($a['city']), $q)) {
                return $a;
            }
        }
        foreach ($all as $a) {
            if (str_contains(strtolower($a['name']), $q)) {
                return $a;
            }
        }

        return null;
    }

    /**
     * Autocomplete airport search.
     */
    public static function searchAirports(string $query, int $limit = 12): array
    {
        $q = strtolower(trim($query));
        $all = self::worldwideAirports();

        // Also merge DB airports if any custom ones exist
        $dbAirports = Airport::all();
        $seen = [];
        $merged = [];

        foreach ($dbAirports as $da) {
            $code = strtoupper($da->code);
            $seen[$code] = true;
            $merged[] = [
                'code' => $code,
                'name' => $da->name,
                'city' => $da->city,
                'country' => $da->country,
                'tz' => $da->timezone ?? 'UTC',
            ];
        }

        foreach ($all as $a) {
            $code = strtoupper($a['code']);
            if (!isset($seen[$code])) {
                $seen[$code] = true;
                $merged[] = $a;
            }
        }

        if ($q === '') {
            return array_slice($merged, 0, $limit);
        }

        $out = [];
        foreach ($merged as $a) {
            if (
                str_contains(strtolower($a['code']), $q) ||
                str_contains(strtolower($a['city']), $q) ||
                str_contains(strtolower($a['name']), $q) ||
                str_contains(strtolower($a['country']), $q)
            ) {
                $out[] = $a;
            }
            if (count($out) >= $limit) {
                break;
            }
        }

        return $out;
    }

    protected static function coord(string $code): ?array
    {
        static $c = [
            'ISB' => [33.55, 72.83], 'KHI' => [24.9, 67.16], 'LHE' => [31.52, 74.4], 'PEW' => [33.99, 71.51],
            'DXB' => [25.25, 55.36], 'AUH' => [24.43, 54.65], 'SHJ' => [25.33, 55.52], 'DOH' => [25.27, 51.6],
            'JED' => [21.68, 39.16], 'RUH' => [24.96, 46.7], 'MED' => [24.55, 39.7], 'KWI' => [29.23, 47.97],
            'IST' => [41.28, 28.75], 'LHR' => [51.47, -0.45], 'CDG' => [49.01, 2.55], 'FRA' => [50.03, 8.57],
            'AMS' => [52.31, 4.76], 'MAD' => [40.47, -3.56], 'JFK' => [40.64, -73.78], 'IAD' => [38.95, -77.46],
            'ORD' => [41.97, -87.9], 'LAX' => [33.94, -118.41], 'YYZ' => [43.68, -79.63], 'DEL' => [28.56, 77.1],
            'BKK' => [13.69, 100.75], 'KUL' => [2.75, 101.7], 'SIN' => [1.36, 103.99], 'HKG' => [22.31, 113.91],
            'PEK' => [40.08, 116.58], 'NRT' => [35.76, 140.39], 'ICN' => [37.46, 126.44], 'SYD' => [-33.95, 151.18],
            'CAI' => [30.12, 31.41], 'JNB' => [-26.14, 28.24], 'NBO' => [-1.32, 36.93], 'MCT' => [23.6, 58.28],
            'DAC' => [23.84, 90.4], 'ATH' => [37.94, 23.94], 'ZRH' => [47.46, 8.55], 'FCO' => [41.8, 12.25],
            'MEL' => [-37.67, 144.84], 'YVR' => [49.19, -123.18], 'SAW' => [40.9, 29.31], 'MAN' => [53.35, -2.27],
            'IKA' => [35.41, 51.15], 'MUX' => [30.20, 71.42], 'SKT' => [32.53, 74.36],
        ];

        return $c[$code] ?? null;
    }

    protected static function hashcode(string $s): int
    {
        $h = 0;
        $len = strlen($s);
        for ($i = 0; $i < $len; $i++) {
            $h = (($h << 5) - $h + ord($s[$i])) & 0xFFFFFFFF;
        }
        return abs($h);
    }

    protected static function seededRandom(int $seed): callable
    {
        $s = $seed;
        return function () use (&$s) {
            $s = ($s * 9301 + 49297) % 233280;
            return $s / 233280;
        };
    }

    protected static function fmtTime(int $minutes): string
    {
        $h = floor($minutes / 60) % 24;
        $m = $minutes % 60;
        $period = $h >= 12 ? 'PM' : 'AM';
        $h12 = $h % 12 === 0 ? 12 : $h % 12;
        return $h12 . ':' . ($m < 10 ? '0' . $m : $m) . ' ' . $period;
    }

    protected static function durationMinutes(array $origin, array $dest): int
    {
        $o = self::coord($origin['code']);
        $d = self::coord($dest['code']);
        if (!$o || !$d) {
            return 420;
        }
        $toRad = fn ($x) => $x * M_PI / 180;
        $R = 6371;
        $dLat = $toRad($d[0] - $o[0]);
        $dLon = $toRad($d[1] - $o[1]);
        $a = sin($dLat / 2) ** 2 + cos($toRad($o[0])) * cos($toRad($d[0])) * sin($dLon / 2) ** 2;
        $dist = 2 * $R * asin(sqrt($a));
        return (int) round(($dist / 800) * 60 + 45);
    }

    /**
     * Deterministic worldwide flight offer generator.
     */
    public static function generateWorldwideOffers(
        array $origin,
        array $dest,
        string $date,
        ?string $cabin = 'Economy'
    ): array {
        $seed = self::hashcode($origin['code'] . '-' . $dest['code'] . '-' . $date);
        $rand = self::seededRandom($seed);
        $baseMin = self::durationMinutes($origin, $dest);
        $hours = intdiv($baseMin, 60);
        $mins = $baseMin % 60;

        $count = 2 + (int) floor($rand() * 4);
        $carriers = [];
        while (count($carriers) < $count) {
            $idx = (int) floor($rand() * count(self::GLOBAL_AIRLINES));
            $c = self::GLOBAL_AIRLINES[$idx];
            if (!in_array($c, $carriers, true)) {
                $carriers[] = $c;
            }
        }

        $fareBase = max(65000, $baseMin * 500);
        $cmult = match ($cabin) {
            'First' => 3.4,
            'Business' => 2.2,
            'Premium Economy' => 1.4,
            default => 1.0,
        };

        $offers = [];
        foreach ($carriers as $i => $carrier) {
            $dep = (int) floor($rand() * 1440);
            $sameRegion = substr($origin['code'], 0, 1) === substr($dest['code'], 0, 1) && $baseMin < 400;
            $stops = $sameRegion ? 0 : (int) floor($rand() * 2);
            $extra = $stops * (90 + (int) floor($rand() * 120));
            $total = $baseMin + $extra;
            $arr = ($dep + $total) % 1440;

            $variance = 0.85 + $rand() * 0.35;
            $publicPrice = (int) round($fareBase * $cmult * $variance);
            $b2bPrice = (int) round($publicPrice * (0.86 + $rand() * 0.05));
            $seats = 1 + (int) floor($rand() * 14);

            $offers[] = [
                'id' => $seed . '-' . $carrier['code'] . '-' . $i,
                'airline' => $carrier['name'],
                'airlineCode' => $carrier['code'],
                'flightNumber' => $carrier['code'] . ' ' . (100 + (int) floor($rand() * 900)),
                'originCode' => $origin['code'],
                'originCity' => $origin['city'],
                'originCountry' => $origin['country'],
                'originAirport' => $origin['name'],
                'destCode' => $dest['code'],
                'destCity' => $dest['city'],
                'destCountry' => $dest['country'],
                'destAirport' => $dest['name'],
                'departureDate' => $date,
                'departureTime' => self::fmtTime($dep),
                'arrivalDate' => $date,
                'arrivalTime' => self::fmtTime($arr),
                'duration' => ($hours + $stops * 2) . 'h ' . ($mins < 10 ? '0' . $mins : $mins) . 'm',
                'stops' => $stops,
                'cabinClass' => $cabin ?? 'Economy',
                'baggage' => $stops === 0 ? '30 KG' : '2 x 23 KG',
                'isRefundable' => $rand() > 0.4,
                'publicPrice' => $publicPrice,
                'b2bPrice' => $b2bPrice,
                'availableSeats' => $seats,
            ];
        }

        usort($offers, fn ($a, $b) => strcmp($a['departureTime'], $b['departureTime']));
        return $offers;
    }

    /**
     * Search tickets from database.
     */
    public static function search(array $filters, ?User $user = null): array
    {
        $from = trim($filters['from'] ?? '');
        $to = trim($filters['to'] ?? '');
        $date = trim($filters['date'] ?? '');
        $cabin = trim($filters['cabin'] ?? '');
        $airline = trim($filters['airline'] ?? '');
        $stops = $filters['stops'] ?? '';
        $refundable = $filters['refundable'] ?? '';
        $sort = $filters['sort'] ?? 'price_asc';
        $maxPrice = !empty($filters['maxPrice']) ? (float) $filters['maxPrice'] : null;

        $originAirport = $from ? self::findAirport($from) : null;
        $destAirport = $to ? self::findAirport($to) : null;

        // Auto materialize worldwide offers if resolved
        if ($originAirport && $destAirport) {
            $apiDate = $date ?: date('Y-m-d');
            $validCabin = ($cabin && $cabin !== 'all' && $cabin !== 'Any') ? $cabin : 'Economy';
            InventoryService::materializeWorldwide($originAirport, $destAirport, $apiDate, $validCabin);
        }

        $query = Ticket::query()->where('available_inventory', '>', 0);

        if ($originAirport) {
            $query->where(function ($q) use ($originAirport) {
                $q->where('dep_airport_code', $originAirport['code'])
                    ->orWhere('dep_city', 'like', '%' . $originAirport['city'] . '%');
            });
        } elseif ($from) {
            $query->where(function ($q) use ($from) {
                $q->where('dep_airport_code', 'like', "%{$from}%")
                    ->orWhere('dep_city', 'like', "%{$from}%")
                    ->orWhere('dep_airport_name', 'like', "%{$from}%");
            });
        }

        if ($destAirport) {
            $query->where(function ($q) use ($destAirport) {
                $q->where('arr_airport_code', $destAirport['code'])
                    ->orWhere('arr_city', 'like', '%' . $destAirport['city'] . '%');
            });
        } elseif ($to) {
            $query->where(function ($q) use ($to) {
                $q->where('arr_airport_code', 'like', "%{$to}%")
                    ->orWhere('arr_city', 'like', "%{$to}%")
                    ->orWhere('arr_airport_name', 'like', "%{$to}%");
            });
        }

        if ($date) {
            $query->where('dep_date', 'like', "%{$date}%");
        }

        if ($cabin && $cabin !== 'all' && $cabin !== 'Any') {
            $query->where('cabin_class', 'like', "%{$cabin}%");
        }

        if ($airline && $airline !== 'all') {
            $query->where('airline_name', 'like', "%{$airline}%");
        }

        if ($stops !== '' && $stops !== 'all' && $stops !== null) {
            $query->where('stops', (int) $stops);
        }

        if ($refundable === 'true' || $refundable === '1') {
            $query->where('is_refundable', true);
        } elseif ($refundable === 'false' || $refundable === '0') {
            $query->where('is_refundable', false);
        }

        $tickets = $query->get();

        // Calculate discount for agents
        $discountPercent = 0.0;
        $isAgent = false;
        if ($user && ($user->isAgent() || $user->isAdmin())) {
            $isAgent = true;
            if ($user->group_id && $user->group) {
                $discountPercent = (float) $user->group->discount_percent;
            }
        }

        // Add computed agent pricing and filter
        $results = $tickets->map(function ($ticket) use ($discountPercent, $isAgent) {
            $b2bFinal = $ticket->b2b_price;
            if ($discountPercent > 0) {
                $b2bFinal = round($b2bFinal * (1 - $discountPercent / 100));
            }

            $effectivePrice = $isAgent ? $b2bFinal : $ticket->public_price;

            $item = $ticket->toArray();
            $item['effective_price'] = $effectivePrice;
            $item['discount_percent'] = $discountPercent;
            $item['final_b2b_price'] = $b2bFinal;
            return $item;
        });

        if ($maxPrice !== null) {
            $results = $results->filter(fn ($t) => $t['effective_price'] <= $maxPrice);
        }

        // Sorting
        $results = $results->values();
        $sorted = $results->sort(function ($a, $b) use ($sort) {
            return match ($sort) {
                'departure_asc' => strcmp($a['dep_time'], $b['dep_time']),
                'departure_desc' => strcmp($b['dep_time'], $a['dep_time']),
                'price_desc' => $b['effective_price'] <=> $a['effective_price'],
                default => $a['effective_price'] <=> $b['effective_price'],
            };
        });

        return array_values($sorted->toArray());
    }
}
