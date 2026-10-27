<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    /**
     * Get public-facing site settings (hero image, site name, etc.)
     */
    public function __invoke(): JsonResponse
    {
        $heroImagePath = SiteSetting::get('hero_image_path');
        $heroImageUrl = null;

        if ($heroImagePath && Storage::disk('public')->exists($heroImagePath)) {
            $heroImageUrl = Storage::disk('public')->url($heroImagePath);
        }

        $logoPath = SiteSetting::get('logo_path');
        $logoUrl = null;

        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            $logoUrl = Storage::disk('public')->url($logoPath);
        }

        $aboutImagePath = SiteSetting::get('about_image_path');
        $aboutImageUrl = null;
        if ($aboutImagePath && Storage::disk('public')->exists($aboutImagePath)) {
            $aboutImageUrl = Storage::disk('public')->url($aboutImagePath);
        }

        $aboutExpImagePath = SiteSetting::get('about_exp_image_path');
        $aboutExpImageUrl = null;
        if ($aboutExpImagePath && Storage::disk('public')->exists($aboutExpImagePath)) {
            $aboutExpImageUrl = Storage::disk('public')->url($aboutExpImagePath);
        }

        return response()->json([
            'data' => [
                'site_name' => SiteSetting::get('site_name', 'EasyTicket Pakistan'),
                'logo_url' => $logoUrl,
                'hero_image_url' => $heroImageUrl,
                'hero_headline' => SiteSetting::get('hero_headline'),
                'hero_subheadline' => SiteSetting::get('hero_subheadline'),
                // About Us Section (Dynamic Admin Panel Controlled)
                'about_enabled' => SiteSetting::get('about_enabled', '1'),
                'about_subtitle' => SiteSetting::get('about_subtitle', 'About Discover'),
                'about_title' => SiteSetting::get('about_title', "Your Trusted Source\nIncredible Journey"),
                'about_description' => SiteSetting::get('about_description', 'Mixtures Of A Metal And Other Elements. They Generally Provide Greater Over Pure Metal, Which Usually Much Softer.'),
                'about_image_url' => $aboutImageUrl,
                'about_feature_1' => SiteSetting::get('about_feature_1', 'First Class Cabin'),
                'about_feature_2' => SiteSetting::get('about_feature_2', 'Business Travel'),
                'about_feature_3' => SiteSetting::get('about_feature_3', 'Premium Economy'),
                'about_feature_4' => SiteSetting::get('about_feature_4', 'Now Selected Routes'),
                'about_button_text' => SiteSetting::get('about_button_text', 'About Us'),
                'about_button_link' => SiteSetting::get('about_button_link', '/about'),
                'about_exp_title' => SiteSetting::get('about_exp_title', 'Destinations'),
                'about_exp_years' => SiteSetting::get('about_exp_years', '39+'),
                'about_exp_label' => SiteSetting::get('about_exp_label', 'YEAR EXPERIENCE'),
                'about_exp_image_url' => $aboutExpImageUrl,

                // Destinations Section (Dynamic Admin Panel Controlled)
                'destinations_tagline' => SiteSetting::get('destinations_tagline', 'Top Global Hubs'),
                'destinations_title' => SiteSetting::get('destinations_title', 'Destinations'),

                // Booking Roadmap Section (Dynamic Admin Panel Controlled)
                'roadmap_enabled' => SiteSetting::get('roadmap_enabled', '1'),
                'roadmap_tagline' => SiteSetting::get('roadmap_tagline', 'Booking Roadmap'),
                'roadmap_title' => SiteSetting::get('roadmap_title', "4 Easy Steps\nSource Incredible Journey"),
                'roadmap_step1_title' => SiteSetting::get('roadmap_step1_title', 'Online Booking'),
                'roadmap_step1_desc' => SiteSetting::get('roadmap_step1_desc', 'Query real-time GDS availability across 14+ international carriers with instant wholesale B2B pricing.'),
                'roadmap_step2_title' => SiteSetting::get('roadmap_step2_title', 'Flight Ticket'),
                'roadmap_step2_desc' => SiteSetting::get('roadmap_step2_desc', 'Lock seats with atomic database decrement and guaranteed 30-min hold with zero price hike risk.'),
                'roadmap_step3_title' => SiteSetting::get('roadmap_step3_title', 'Confirm Travel'),
                'roadmap_step3_desc' => SiteSetting::get('roadmap_step3_desc', 'Review verified passenger manifests and finalize complete multi-sector itineraries in one click.'),
                'roadmap_step4_title' => SiteSetting::get('roadmap_step4_title', 'Easy Payments'),
                'roadmap_step4_desc' => SiteSetting::get('roadmap_step4_desc', 'Fast local IBFT settlements and instant 6-character PNR ticket issuance with branded print-ready PDFs.'),

                // Service Guarantees Section
                'guarantees_enabled' => SiteSetting::get('guarantees_enabled', '1'),
                'guarantees_tagline' => SiteSetting::get('guarantees_tagline', 'Travel With Complete Confidence'),
                'guarantees_title' => SiteSetting::get('guarantees_title', 'Backed by Round-the-Clock Care & Guarantees'),

                // Travel Milestones Stats
                'stats_bar_enabled' => SiteSetting::get('stats_bar_enabled', '1'),
                'stat1_value' => SiteSetting::get('stat1_value', '700k+'),
                'stat1_label' => SiteSetting::get('stat1_label', 'Flights booked'),
                'stat2_value' => SiteSetting::get('stat2_value', '300k+'),
                'stat2_label' => SiteSetting::get('stat2_label', 'Buses booked'),
                'stat3_value' => SiteSetting::get('stat3_value', '50k+'),
                'stat3_label' => SiteSetting::get('stat3_label', 'Hotels booked'),
                'stat4_value' => SiteSetting::get('stat4_value', '20m+'),
                'stat4_label' => SiteSetting::get('stat4_label', 'Kilometres traveled'),

                // Footer & Contacts (Dynamic Admin Panel Controlled)
                'footer_description' => SiteSetting::get('footer_description', "Pakistan's leading B2B air consolidation platform. Empowering travel agencies with wholesale flight inventory, instant seat locks, and transparent payment settlement."),
                'footer_license_text' => SiteSetting::get('footer_license_text', 'Department of Tourist Services (DTS) Licensed Operations.'),
                'footer_support_title' => SiteSetting::get('footer_support_title', 'Islamabad HQ Support'),
                'footer_address' => SiteSetting::get('footer_address', SiteSetting::get('office_address', 'ISE Towers, 55-B Jinnah Avenue, Blue Area, Islamabad, Pakistan.')),
                'footer_phone' => SiteSetting::get('footer_phone', SiteSetting::get('contact_phone', SiteSetting::get('support_phone', '+92 51 280 1199'))),
                'footer_whatsapp' => SiteSetting::get('footer_whatsapp', SiteSetting::get('support_whatsapp', '+92 300 1234567')),
                'footer_email' => SiteSetting::get('footer_email', SiteSetting::get('contact_email', 'operations@easyticket.com.pk')),
                'footer_support_badge' => SiteSetting::get('footer_support_badge', '24/7 Ticketing Desk'),
                'footer_hubs_sectors' => SiteSetting::get('footer_hubs_sectors', "Islamabad (ISB) ⇄ Dubai (DXB)\nLahore (LHE) ⇄ Jeddah (JED)\nKarachi (KHI) ⇄ London (LHR)\nPeshawar (PEW) ⇄ Riyadh (RUH)\nSialkot (SKT) ⇄ Sharjah (SHJ)"),
                'footer_bank_partners' => SiteSetting::get('footer_bank_partners', 'Meezan Bank (Islamic), Habib Bank Limited (HBL), Bank Alfalah, Allied Bank (ABL)'),
                'footer_copyright' => SiteSetting::get('footer_copyright', 'EasyTicket (Pvt) Ltd. All Rights Reserved.'),
                'footer_security_note' => SiteSetting::get('footer_security_note', 'Secure SSL 256-Bit Encrypted'),
                'footer_dts_reg' => SiteSetting::get('footer_dts_reg', 'DTS Reg # HQ-OPS-001'),
                'footer_iata_text' => SiteSetting::get('footer_iata_text', 'IATA Standard e-Ticketing'),
            ],
        ]);
    }
}
