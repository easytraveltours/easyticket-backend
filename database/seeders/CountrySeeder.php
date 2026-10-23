<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'Pakistan', 'code' => 'PK', 'flag_emoji' => '🇵🇰', 'display_order' => 1],
            ['name' => 'United Arab Emirates', 'code' => 'AE', 'flag_emoji' => '🇦🇪', 'display_order' => 2],
            ['name' => 'Saudi Arabia', 'code' => 'SA', 'flag_emoji' => '🇸🇦', 'display_order' => 3],
            ['name' => 'Qatar', 'code' => 'QA', 'flag_emoji' => '🇶🇦', 'display_order' => 4],
            ['name' => 'United Kingdom', 'code' => 'GB', 'flag_emoji' => '🇬🇧', 'display_order' => 5],
            ['name' => 'Turkey', 'code' => 'TR', 'flag_emoji' => '🇹🇷', 'display_order' => 6],
            ['name' => 'Thailand', 'code' => 'TH', 'flag_emoji' => '🇹🇭', 'display_order' => 7],
            ['name' => 'Malaysia', 'code' => 'MY', 'flag_emoji' => '🇲🇾', 'display_order' => 8],
            ['name' => 'United States', 'code' => 'US', 'flag_emoji' => '🇺🇸', 'display_order' => 9],
            ['name' => 'Canada', 'code' => 'CA', 'flag_emoji' => '🇨🇦', 'display_order' => 10],
            ['name' => 'Australia', 'code' => 'AU', 'flag_emoji' => '🇦🇺', 'display_order' => 11],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(['code' => $country['code']], $country);
        }
    }
}
