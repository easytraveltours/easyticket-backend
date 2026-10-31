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
                'footer_email' => SiteSetting::get('footer_email', SiteSetting::get('contact_email', 'info@easyticket.com.pk')),
                'footer_support_badge' => SiteSetting::get('footer_support_badge', '24/7 Ticketing Desk'),
                'footer_hubs_sectors' => SiteSetting::get('footer_hubs_sectors', "Islamabad (ISB) ⇄ Dubai (DXB)\nLahore (LHE) ⇄ Jeddah (JED)\nKarachi (KHI) ⇄ London (LHR)\nPeshawar (PEW) ⇄ Riyadh (RUH)\nSialkot (SKT) ⇄ Sharjah (SHJ)"),
                'footer_bank_partners' => SiteSetting::get('footer_bank_partners', 'Meezan Bank (Islamic), Habib Bank Limited (HBL), Bank Alfalah, Allied Bank (ABL)'),
                'footer_copyright' => SiteSetting::get('footer_copyright', 'EasyTicket (Pvt) Ltd. All Rights Reserved.'),
                'footer_security_note' => SiteSetting::get('footer_security_note', 'Secure SSL 256-Bit Encrypted'),
                'footer_dts_reg' => SiteSetting::get('footer_dts_reg', 'DTS Reg # HQ-OPS-001'),
                'footer_iata_text' => SiteSetting::get('footer_iata_text', 'IATA Standard e-Ticketing'),

                // ── About Page Dynamic Content ──
                'about_page_badge' => SiteSetting::get('about_page_badge', 'Company Overview'),
                'about_page_title' => SiteSetting::get('about_page_title', 'Pioneering Air Ticketing for Pakistan'),
                'about_page_description' => SiteSetting::get('about_page_description', 'Founded in Islamabad, EasyTicket provides cutting-edge consolidation and B2B flight distribution technology for independent travel agencies across Pakistan.'),
                'about_page_story_title' => SiteSetting::get('about_page_story_title', 'Empowering Independent Travel Agencies Across Pakistan'),
                'about_page_story' => SiteSetting::get('about_page_story', "Launched to solve chronic seat inventory bottlenecks, unpredictable flight tariff surcharges, and slow manual ticketing desks, EasyTicket consolidates wholesale seat inventory across 14+ top regional and global airlines into one unified platform.\n\nToday, independent agencies and corporate travel desks across all major Pakistani commercial hubs rely on our atomic seat holds, automated issuance engines, and 24/7 dedicated operational desks."),
                'about_page_image' => SiteSetting::get('about_page_image', '/images/destinations/dubai.jpg'),
                'about_stat1_val' => SiteSetting::get('about_stat1_val', '14+'),
                'about_stat1_lbl' => SiteSetting::get('about_stat1_lbl', 'Partner Airlines'),
                'about_stat2_val' => SiteSetting::get('about_stat2_val', '1,200+'),
                'about_stat2_lbl' => SiteSetting::get('about_stat2_lbl', 'Registered Agencies'),
                'about_stat3_val' => SiteSetting::get('about_stat3_val', '99.9%'),
                'about_stat3_lbl' => SiteSetting::get('about_stat3_lbl', 'Issuance Uptime'),
                'about_stat4_val' => SiteSetting::get('about_stat4_val', '24/7'),
                'about_stat4_lbl' => SiteSetting::get('about_stat4_lbl', 'Operations Desk'),
                'about_card1_title' => SiteSetting::get('about_card1_title', '100% Reliable Holds'),
                'about_card1_desc' => SiteSetting::get('about_card1_desc', 'Eliminating traditional double-booking dilemmas through automated atomic inventory locks with strict 30-minute passenger confirmation periods.'),
                'about_card2_title' => SiteSetting::get('about_card2_title', 'Direct Wholesale Fares'),
                'about_card2_desc' => SiteSetting::get('about_card2_desc', 'Partnered directly with major Middle Eastern, European, and Gulf carriers to secure wholesale rates that guarantee higher agent commission spreads.'),
                'about_card3_title' => SiteSetting::get('about_card3_title', 'Local Operational Support'),
                'about_card3_desc' => SiteSetting::get('about_card3_desc', 'Our Islamabad HQ ticketing operations desk is manned 24/7 to verify bank deposits, reissue tickets, and handle itinerary changes in real time.'),
                'about_cta_title' => SiteSetting::get('about_cta_title', 'Ready to Scale Your Travel Agency Revenue?'),
                'about_cta_desc' => SiteSetting::get('about_cta_desc', 'Get approved in under 24 hours and start issuing wholesale tickets today with dedicated support.'),
                'about_cta_btn' => SiteSetting::get('about_cta_btn', 'Become an Agent Partner'),

                // ── B2B Services Page Dynamic Content ──
                'b2b_page_badge' => SiteSetting::get('b2b_page_badge', 'Commercial Tiers & Margins'),
                'b2b_page_title' => SiteSetting::get('b2b_page_title', 'Transparent Margins for Every Agency Scale'),
                'b2b_page_description' => SiteSetting::get('b2b_page_description', 'Whether you are an independent boutique agency or a multi-branch network, EasyTicket provides volume-based profitability.'),
                'b2b_tier1_name' => SiteSetting::get('b2b_tier1_name', 'Standard Agency'),
                'b2b_tier1_discount' => SiteSetting::get('b2b_tier1_discount', 'Wholesale B2B Rate'),
                'b2b_tier1_credit' => SiteSetting::get('b2b_tier1_credit', 'Pre-paid Deposit'),
                'b2b_tier1_features' => SiteSetting::get('b2b_tier1_features', "Full flight search access\n30-minute guaranteed holds\nOnline IBFT deposit verification\nStandard email support"),
                'b2b_tier1_cta' => SiteSetting::get('b2b_tier1_cta', 'Standard Signup'),
                'b2b_tier2_name' => SiteSetting::get('b2b_tier2_name', 'Gold Travel Partner'),
                'b2b_tier2_discount' => SiteSetting::get('b2b_tier2_discount', '3% Off Wholesale'),
                'b2b_tier2_credit' => SiteSetting::get('b2b_tier2_credit', 'PKR 150,000 Credit Line'),
                'b2b_tier2_features' => SiteSetting::get('b2b_tier2_features', "Priority ticket issuance\nInstant hold-to-confirm\nDedicated WhatsApp commercial desk\nCustom branded e-tickets"),
                'b2b_tier2_cta' => SiteSetting::get('b2b_tier2_cta', 'Upgrade to Gold'),
                'b2b_tier2_tag' => SiteSetting::get('b2b_tier2_tag', 'Most Popular for Active Agencies'),
                'b2b_tier3_name' => SiteSetting::get('b2b_tier3_name', 'Platinum Elite Consolidator'),
                'b2b_tier3_discount' => SiteSetting::get('b2b_tier3_discount', '6% to 10% Off Wholesale'),
                'b2b_tier3_credit' => SiteSetting::get('b2b_tier3_credit', 'PKR 500,000+ Credit Line'),
                'b2b_tier3_features' => SiteSetting::get('b2b_tier3_features', "Sub-agent management API\nZero change penalty on select airlines\nWeekly bank credit settlement\nExecutive operations manager"),
                'b2b_tier3_cta' => SiteSetting::get('b2b_tier3_cta', 'Commercial Agreement'),
                'b2b_benefits_title' => SiteSetting::get('b2b_benefits_title', 'Institutional Advantages for Travel Agencies'),
                'b2b_benefits_subtitle' => SiteSetting::get('b2b_benefits_subtitle', 'Engineered to eliminate monthly GDS terminal lease fees, restrictive bank guarantees, and unexpected debit memos.'),

                // ── Contact Desk Page Dynamic Content ──
                'contact_page_badge' => SiteSetting::get('contact_page_badge', 'Support & Commercial Desk'),
                'contact_page_title' => SiteSetting::get('contact_page_title', 'Get in Touch with EasyTicket'),
                'contact_page_description' => SiteSetting::get('contact_page_description', 'Need assistance with agency onboarding, corporate deposits, or urgent ticket issuance? Our team is standing by.'),
                'contact_page_hq_title' => SiteSetting::get('contact_page_hq_title', 'Headquarters'),
                'contact_page_address' => SiteSetting::get('contact_page_address', 'ISE Towers, 55-B Jinnah Avenue, Blue Area, Islamabad, Pakistan'),
                'contact_page_phone_title' => SiteSetting::get('contact_page_phone_title', 'Direct Phone'),
                'contact_page_phone' => SiteSetting::get('contact_page_phone', '+92 51 280 1199'),
                'contact_page_phone_note' => SiteSetting::get('contact_page_phone_note', 'Available 24 Hours / 7 Days'),
                'contact_page_email_title' => SiteSetting::get('contact_page_email_title', 'Official Email'),
                'contact_page_email' => SiteSetting::get('contact_page_email', 'info@easyticket.com.pk'),
                'contact_page_email_note' => SiteSetting::get('contact_page_email_note', 'Commercial & settlement inquiries'),
                'contact_page_whatsapp_title' => SiteSetting::get('contact_page_whatsapp_title', 'WhatsApp Live Desk'),
                'contact_page_whatsapp' => SiteSetting::get('contact_page_whatsapp', '+92 300 1234567'),
                'contact_page_whatsapp_note' => SiteSetting::get('contact_page_whatsapp_note', 'Instant Ticketing & Hold Support'),
                'contact_page_hours_title' => SiteSetting::get('contact_page_hours_title', 'Operations Schedule'),
                'contact_page_hours' => SiteSetting::get('contact_page_hours', 'Round-the-clock 24/7 Ticketing Desk & Emergency Voiding Operations'),
            ],
        ]);
    }
}
