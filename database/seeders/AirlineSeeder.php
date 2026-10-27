<?php

namespace Database\Seeders;

use App\Models\Airline;
use Illuminate\Database\Seeder;

class AirlineSeeder extends Seeder
{
    public function run(): void
    {
        $airlines = [
            ['name' => 'Emirates Airline', 'iata_code' => 'EK', 'icao_code' => 'UAE', 'country' => 'United Arab Emirates'],
            ['name' => 'Qatar Airways', 'iata_code' => 'QR', 'icao_code' => 'QTR', 'country' => 'Qatar'],
            ['name' => 'Turkish Airlines', 'iata_code' => 'TK', 'icao_code' => 'THY', 'country' => 'Turkey'],
            ['name' => 'Pakistan International Airlines', 'iata_code' => 'PK', 'icao_code' => 'PIA', 'country' => 'Pakistan'],
            ['name' => 'Oman Air', 'iata_code' => 'WY', 'icao_code' => 'OMA', 'country' => 'Oman'],
            ['name' => 'Fly Dubai', 'iata_code' => 'FZ', 'icao_code' => 'FDB', 'country' => 'United Arab Emirates'],
            ['name' => 'Etihad Airways', 'iata_code' => 'EY', 'icao_code' => 'ETD', 'country' => 'United Arab Emirates'],
            ['name' => 'Air Sial', 'iata_code' => 'PF', 'icao_code' => 'SIF', 'country' => 'Pakistan'],
            ['name' => 'Virgin Atlantic', 'iata_code' => 'VS', 'icao_code' => 'VIR', 'country' => 'United Kingdom'],
            ['name' => 'Thai Airways', 'iata_code' => 'TG', 'icao_code' => 'THA', 'country' => 'Thailand'],
            ['name' => 'Kuwait Airways', 'iata_code' => 'KU', 'icao_code' => 'KAC', 'country' => 'Kuwait'],
            ['name' => 'China Southern', 'iata_code' => 'CZ', 'icao_code' => 'CSN', 'country' => 'China'],
            ['name' => 'Saudi Arabian', 'iata_code' => 'SV', 'icao_code' => 'SVA', 'country' => 'Saudi Arabia'],
            ['name' => 'Air Arabia', 'iata_code' => 'G9', 'icao_code' => 'ABY', 'country' => 'United Arab Emirates'],
            ['name' => 'Air Malindo', 'iata_code' => 'OD', 'icao_code' => 'MXD', 'country' => 'Malaysia'],
            ['name' => 'British Airways', 'iata_code' => 'BA', 'icao_code' => 'BAW', 'country' => 'United Kingdom'],
            ['name' => 'flyadeal', 'iata_code' => 'F3', 'icao_code' => 'FAD', 'country' => 'Saudi Arabia'],
            ['name' => 'SalamAir', 'iata_code' => 'OV', 'icao_code' => 'OMS', 'country' => 'Oman'],
            ['name' => 'Flynas', 'iata_code' => 'XY', 'icao_code' => 'KNE', 'country' => 'Saudi Arabia'],
            ['name' => 'Fly Jinnah', 'iata_code' => '9P', 'icao_code' => 'FJL', 'country' => 'Pakistan'],
            ['name' => 'Airblue', 'iata_code' => 'PA', 'icao_code' => 'ABQ', 'country' => 'Pakistan'],
        ];

        foreach ($airlines as $airline) {
            Airline::updateOrCreate(['iata_code' => $airline['iata_code']], $airline);
        }
    }
}
