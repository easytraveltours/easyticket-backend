<?php

namespace Database\Seeders;

use App\Models\Airport;
use Illuminate\Database\Seeder;

class AirportSeeder extends Seeder
{
    public function run(): void
    {
        $airports = [
            ['name' => 'Islamabad International Airport', 'code' => 'ISB', 'city' => 'Islamabad', 'country' => 'Pakistan'],
            ['name' => 'Allama Iqbal International Airport', 'code' => 'LHE', 'city' => 'Lahore', 'country' => 'Pakistan'],
            ['name' => 'Jinnah International Airport', 'code' => 'KHI', 'city' => 'Karachi', 'country' => 'Pakistan'],
            ['name' => 'Bacha Khan International Airport', 'code' => 'PEW', 'city' => 'Peshawar', 'country' => 'Pakistan'],
            ['name' => 'Dubai International Airport', 'code' => 'DXB', 'city' => 'Dubai', 'country' => 'United Arab Emirates'],
            ['name' => 'Abu Dhabi International Airport', 'code' => 'AUH', 'city' => 'Abu Dhabi', 'country' => 'United Arab Emirates'],
            ['name' => 'Sharjah International Airport', 'code' => 'SHJ', 'city' => 'Sharjah', 'country' => 'United Arab Emirates'],
            ['name' => 'King Abdulaziz International Airport', 'code' => 'JED', 'city' => 'Jeddah', 'country' => 'Saudi Arabia'],
            ['name' => 'King Khalid International Airport', 'code' => 'RUH', 'city' => 'Riyadh', 'country' => 'Saudi Arabia'],
            ['name' => 'Prince Mohammad bin Abdulaziz Airport', 'code' => 'MED', 'city' => 'Medina', 'country' => 'Saudi Arabia'],
            ['name' => 'Hamad International Airport', 'code' => 'DOH', 'city' => 'Doha', 'country' => 'Qatar'],
            ['name' => 'Heathrow Airport', 'code' => 'LHR', 'city' => 'London', 'country' => 'United Kingdom'],
            ['name' => 'Istanbul Airport', 'code' => 'IST', 'city' => 'Istanbul', 'country' => 'Turkey'],
            ['name' => 'Suvarnabhumi Airport', 'code' => 'BKK', 'city' => 'Bangkok', 'country' => 'Thailand'],
            ['name' => 'Kuala Lumpur International Airport', 'code' => 'KUL', 'city' => 'Kuala Lumpur', 'country' => 'Malaysia'],
        ];

        foreach ($airports as $airport) {
            Airport::updateOrCreate(['code' => $airport['code']], $airport);
        }
    }
}
