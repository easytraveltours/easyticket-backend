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
            ['key' => 'footer_description', 'value' => "Pakistan's leading B2B air consolidation platform. Empowering travel agencies with wholesale flight inventory, instant seat locks, and transparent payment settlement.", 'description' => 'Footer Company Bio'],
            ['key' => 'footer_license_text', 'value' => 'Department of Tourist Services (DTS) Licensed Operations.', 'description' => 'Footer Regulatory License Text'],
            ['key' => 'footer_support_title', 'value' => 'Islamabad HQ Support', 'description' => 'Footer Support Office Section Title'],
            ['key' => 'footer_address', 'value' => 'ISE Towers, 55-B Jinnah Avenue, Blue Area, Islamabad, Pakistan.', 'description' => 'Footer Office Address'],
            ['key' => 'footer_phone', 'value' => '+92 51 280 1199', 'description' => 'Footer Direct Desk Telephone'],
            ['key' => 'footer_whatsapp', 'value' => '+92 300 1234567', 'description' => 'Footer Support WhatsApp Helpline'],
            ['key' => 'footer_support_badge', 'value' => '24/7 Ticketing Desk', 'description' => 'Footer Support Badge Text'],
            ['key' => 'footer_hubs_sectors', 'value' => "Islamabad (ISB) ⇄ Dubai (DXB)\nLahore (LHE) ⇄ Jeddah (JED)\nKarachi (KHI) ⇄ London (LHR)\nPeshawar (PEW) ⇄ Riyadh (RUH)\nSialkot (SKT) ⇄ Sharjah (SHJ)", 'description' => 'Footer Hubs & Sectors List'],
            ['key' => 'footer_bank_partners', 'value' => 'Meezan Bank (Islamic), Habib Bank Limited (HBL), Bank Alfalah, Allied Bank (ABL)', 'description' => 'Footer Settlement Bank Partner Names'],
            ['key' => 'footer_copyright', 'value' => 'EasyTicket (Pvt) Ltd. All Rights Reserved.', 'description' => 'Footer Copyright Statement'],
            ['key' => 'footer_security_note', 'value' => 'Secure SSL 256-Bit Encrypted', 'description' => 'Footer Security Notice'],
            ['key' => 'footer_dts_reg', 'value' => 'DTS Reg # HQ-OPS-001', 'description' => 'Footer DTS Registration Number'],
            ['key' => 'footer_iata_text', 'value' => 'IATA Standard e-Ticketing', 'description' => 'Footer IATA Standard Notice'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
