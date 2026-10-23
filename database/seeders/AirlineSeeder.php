<?php

namespace Database\Seeders;

use App\Models\Airline;
use Illuminate\Database\Seeder;

class AirlineSeeder extends Seeder
{
    public function run(): void
    {
        $airlines = [
            ['name' => 'Emirates', 'iata_code' => 'EK', 'icao_code' => 'UAE', 'country' => 'United Arab Emirates'],
            ['name' => 'Flydubai', 'iata_code' => 'FZ', 'icao_code' => 'FDB', 'country' => 'United Arab Emirates'],
            ['name' => 'Pakistan International Airlines', 'iata_code' => 'PK', 'icao_code' => 'PIA', 'country' => 'Pakistan'],
            ['name' => 'Qatar Airways', 'iata_code' => 'QR', 'icao_code' => 'QTR', 'country' => 'Qatar'],
            ['name' => 'Saudia', 'iata_code' => 'SV', 'icao_code' => 'SVA', 'country' => 'Saudi Arabia'],
            ['name' => 'Airblue', 'iata_code' => 'PA', 'icao_code' => 'ABQ', 'country' => 'Pakistan'],
            ['name' => 'Turkish Airlines', 'iata_code' => 'TK', 'icao_code' => 'THY', 'country' => 'Turkey'],
        ];

        foreach ($airlines as $airline) {
            Airline::updateOrCreate(['iata_code' => $airline['iata_code']], $airline);
        }
    }
}
