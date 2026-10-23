<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AgentGroupSeeder::class,
            UserSeeder::class,
            CountrySeeder::class,
            AirportSeeder::class,
            AirlineSeeder::class,
            PaymentMethodSeeder::class,
            SiteSettingSeeder::class,
            TicketSeeder::class,
        ]);
    }
}
