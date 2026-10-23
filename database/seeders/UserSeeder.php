<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'EasyTicket Operations',
                'email' => 'admin@easyticket.com.pk',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'active',
                'phone' => '+92 300 1234567',
                'whatsapp' => '+92 300 1234567',
                'company_name' => 'EasyTicket HQ Pakistan',
                'travel_agency_name' => 'EasyTicket',
                'registration_number' => 'HQ-OPS-001',
                'business_address' => 'ISE Towers, Blue Area, Islamabad',
                'country' => 'Pakistan',
                'city' => 'Islamabad',
                'website' => 'https://easyticket.com.pk',
                'expected_monthly_bookings' => '100+',
                'years_in_business' => '10+',
                'group_id' => 1,
                'wallet_balance' => 0.00,
                'credit_limit' => 0.00,
            ],
            [
                'name' => 'Tariq Mahmood',
                'email' => 'agent@almadina.pk',
                'password' => Hash::make('agent123'),
                'role' => 'agent',
                'status' => 'active',
                'phone' => '+92 321 4455667',
                'whatsapp' => '+92 321 4455667',
                'company_name' => 'Al-Madina Travel & Tours Pvt Ltd',
                'travel_agency_name' => 'Al-Madina Travels',
                'registration_number' => 'DTS-ISB-2018-9941',
                'business_address' => 'Office 402, Blue Area, Jinnah Avenue, Islamabad',
                'country' => 'Pakistan',
                'city' => 'Islamabad',
                'website' => 'https://almadinatravels.pk',
                'expected_monthly_bookings' => '50-100 tickets',
                'years_in_business' => '8',
                'group_id' => 1,
                'wallet_balance' => 185000.00,
                'credit_limit' => 300000.00,
            ],
            [
                'name' => 'Zeeshan Khan',
                'email' => 'zeeshan@falconair.pk',
                'password' => Hash::make('agent123'),
                'role' => 'agent',
                'status' => 'active',
                'phone' => '+92 333 9988771',
                'whatsapp' => '+92 333 9988771',
                'company_name' => 'Falcon Express Travel Network',
                'travel_agency_name' => 'Falcon Express',
                'registration_number' => 'DTS-LHR-2020-5112',
                'business_address' => 'Suite 12, Gulberg III, Lahore',
                'country' => 'Pakistan',
                'city' => 'Lahore',
                'website' => 'https://falcontravel.pk',
                'expected_monthly_bookings' => '20-50 tickets',
                'years_in_business' => '5',
                'group_id' => 2,
                'wallet_balance' => 72000.00,
                'credit_limit' => 150000.00,
            ],
            [
                'name' => 'Hamza Farooq',
                'email' => 'hamza@karachiholidays.com',
                'password' => Hash::make('agent123'),
                'role' => 'agent',
                'status' => 'pending',
                'phone' => '+92 302 5566778',
                'whatsapp' => '+92 302 5566778',
                'company_name' => 'Karachi Horizon Holidays',
                'travel_agency_name' => 'Horizon Holidays',
                'registration_number' => 'DTS-KHI-2023-8821',
                'business_address' => 'Clifton Block 5, Karachi',
                'country' => 'Pakistan',
                'city' => 'Karachi',
                'website' => 'https://karachiholidays.com',
                'expected_monthly_bookings' => '10-25 tickets',
                'years_in_business' => '2',
                'group_id' => 4,
                'wallet_balance' => 0.00,
                'credit_limit' => 0.00,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}
