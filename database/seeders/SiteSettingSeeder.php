<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'EasyTicket', 'description' => 'Website Brand Name'],
            ['key' => 'site_url', 'value' => 'easyticket.com.pk', 'description' => 'Site Domain URL'],
            ['key' => 'support_phone', 'value' => '+92 51 2890111', 'description' => 'Support Telephone Line'],
            ['key' => 'support_whatsapp', 'value' => '+92 300 1234567', 'description' => 'Primary WhatsApp Helpline'],
            ['key' => 'support_email', 'value' => 'support@easyticket.com.pk', 'description' => 'Customer Care Email'],
            ['key' => 'b2b_sales_email', 'value' => 'b2b@easyticket.com.pk', 'description' => 'B2B Agency Inquiries'],
            ['key' => 'office_address', 'value' => 'Level 6, ISE Towers, Jinnah Avenue, Blue Area, Islamabad, Pakistan', 'description' => 'Head Office Address'],
            ['key' => 'reservation_expiry_minutes', 'value' => '30', 'description' => 'Hold Expiry in Minutes'],
            ['key' => 'low_stock_threshold', 'value' => '3', 'description' => 'Low Stock Warning Badge Threshold'],
            ['key' => 'whatsapp_enabled', 'value' => 'true', 'description' => 'Enable WhatsApp integration'],
            ['key' => 'currency_symbol', 'value' => 'PKR', 'description' => 'Default Display Currency'],
            ['key' => 'flight_api_provider', 'value' => 'auto', 'description' => 'Flight API provider mode'],
            ['key' => 'amadeus_client_id', 'value' => '', 'description' => 'Amadeus GDS Client ID'],
            ['key' => 'amadeus_client_secret', 'value' => '', 'description' => 'Amadeus GDS Client Secret'],
            ['key' => 'aviationstack_api_key', 'value' => '', 'description' => 'Aviationstack API Key'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
