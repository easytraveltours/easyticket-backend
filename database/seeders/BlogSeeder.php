<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'The Complete 2026 Umrah Flight Booking & Group Ticketing Guide for Pakistani Travel Agents',
                'slug' => 'complete-2026-umrah-flight-booking-guide-pakistan',
                'category' => 'Umrah & Hajj',
                'excerpt' => 'A strategic operational guide for travel agencies handling high-volume pilgrim groups. Learn how wholesale seat locks, direct Jeddah & Medina connections, and flexible settlement terms maximize profitability.',
                'content' => "## Navigating Umrah Peak Seasons with Confidence\n\nFor Pakistani travel agencies, Umrah operations represent the single largest volume driver throughout the calendar year, particularly during Rajab, Sha'ban, and the holy month of Ramadan. However, managing sudden fare fluctuations, GDS seat drops, and group ticket issuance deadlines can severely impact an agency's profit margins.\n\n### 1. Direct vs. One-Stop Transit Hubs\n\nWhile direct flights with carriers like Saudia, PIA, and Serene Air offer unmatched convenience for elder pilgrims, one-stop itineraries via Gulf transit hubs (flydubai via DXB, Air Arabia via SHJ, Qatar Airways via DOH, and Gulf Air via BAH) frequently provide 15% to 25% lower wholesale cost bases.\n\n### 2. Group Seat Locking Strategies\n\n- **Pre-lock inventory at least 45 to 60 days in advance** for Ramadan sectors.\n- **Verify passenger passport details before final issuance** to prevent non-refundable name correction penalties.\n- **Maintain an active corporate wallet balance** on the EasyTicket B2B portal to ensure instant PNR issuance without banking delays.\n\n### 3. Understanding Saudi Visa & Sector Rules\n\nEnsure that the entry and exit ports match your client's tourist or Umrah visa specifications. Utilizing dual-city ticketing (e.g., Arrive JED / Depart MED) eliminates strenuous 6-hour highway road transfers between the Holy Cities and improves pilgrim satisfaction.",
                'image' => '/images/destinations/jeddah.jpg',
                'author_name' => 'Tariq Mehmood, Chief Aviation Specialist',
                'read_time' => '5 min read',
                'tags' => ['Umrah', 'Saudia', 'B2B Flights', 'Jeddah', 'Pilgrimage'],
                'is_published' => true,
                'is_featured' => true,
                'views_count' => 420,
                'published_at' => Carbon::now()->subDays(3),
            ],
            [
                'title' => 'How Wholesale B2B Air Ticketing Portals Are Replacing Legacy GDS Terminals',
                'slug' => 'how-wholesale-b2b-ticketing-portals-replace-legacy-gds',
                'category' => 'Industry Insights',
                'excerpt' => 'Discover how modern unified air inventory consolidation platforms eliminate monthly GDS equipment leases, debit memos (ADMs), and restrictive bank guarantee requirements for emerging travel agencies.',
                'content' => "## The Evolution of Travel Agency Inventory\n\nDecades ago, launching an IATA travel agency required extensive financial guarantees, complex cryptic code terminal training, and substantial upfront capital. Today, modern B2B air inventory aggregators like EasyTicket Pakistan are leveling the playing field for agencies across Lahore, Karachi, Islamabad, Peshawar, and Sialkot.\n\n### Key Advantages of B2B Aggregation:\n\n1. **Zero ADM Liability**: Because wholesale tickets are validated directly through consolidated inventory contracts, agencies avoid surprise carrier debit memos.\n2. **Transparent Tiered Markups**: Sub-agents gain complete visibility over gross wholesale fares and net commissions.\n3. **Instant Multi-Channel Support**: Dedicated 24/7 ticketing support desks resolve urgent schedule changes and baggage queries in real time.\n\nBy leveraging aggregated volume purchasing, small and medium agencies can now offer competitive fares on major European and Middle Eastern carriers without maintaining multi-million rupee bank guarantees.",
                'image' => '/images/destinations/dubai.jpg',
                'author_name' => 'Farhan Qureshi, Airline Partnerships',
                'read_time' => '4 min read',
                'tags' => ['B2B Portal', 'Travel Agents', 'GDS', 'Aviation Technology'],
                'is_published' => true,
                'is_featured' => true,
                'views_count' => 310,
                'published_at' => Carbon::now()->subDays(6),
            ],
            [
                'title' => 'Top Direct & One-Stop Flight Routes Connecting Pakistan to the UK & Europe',
                'slug' => 'top-flight-routes-pakistan-to-uk-and-europe',
                'category' => 'Routes & Airlines',
                'excerpt' => 'An essential sector breakdown analyzing baggage allowances, transit times, and wholesale pricing trends across London Heathrow, Manchester, Birmingham, and European capitals.',
                'content' => "## High-Demand Transatlantic and European Corridors\n\nThe Pakistani diaspora in the United Kingdom and Western Europe fuels consistent year-round passenger traffic across student, business, and family visit categories. Understanding route dynamics helps travel agents recommend optimal flight schedules to their corporate and retail clients.\n\n### Prime UK Gateways\n\n- **Islamabad (ISB) ⇄ London (LHR / LGW)**: Consistently top-ranking in passenger volume with high demand for premium economy and flexible baggage.\n- **Lahore (LHE) ⇄ Manchester (MAN)**: High-density corridor with preferred connections via Turkish Airlines (IST), Qatar Airways (DOH), and Emirates (DXB).\n- **Karachi (KHI) ⇄ Birmingham (BHX)**: Fast-growing demand among corporate travelers and university students.\n\n### Baggage Policies & Weight Considerations\n\nInternational passengers flying to the UK prioritize 2-piece (46kg total) standard checked luggage allowances. Ensure you check ticket fare rules on EasyTicket before issuance to verify whether baggage is piece-based or weight-based.",
                'image' => '/images/destinations/london.jpg',
                'author_name' => 'Ayesha Siddiqui, Route Analyst',
                'read_time' => '6 min read',
                'tags' => ['UK Flights', 'London', 'Europe', 'Turkish Airlines', 'Baggage'],
                'is_published' => true,
                'is_featured' => false,
                'views_count' => 245,
                'published_at' => Carbon::now()->subDays(10),
            ],
            [
                'title' => '5 Ways Travel Agencies Can Prevent Double-Bookings and Flight Cancellation Pitfalls',
                'slug' => '5-ways-travel-agencies-prevent-double-bookings',
                'category' => 'Best Practices',
                'excerpt' => 'Practical operational precautions to safeguard your agency reputation, maintain client trust, and streamline ticket voiding and refund workflows.',
                'content' => "## Protecting Your Agency Reputation\n\nIn the fast-paced ticketing industry, double-bookings, expired payment windows, and misspelled passenger names can cause financial losses and customer dissatisfaction.\n\n### 1. Always Verify Name Spellings Against Passport MRZ\n\nAlways ask clients for a high-resolution photo of their passport bio page. Name variations (e.g., swapping first name and middle name) can lead to boarding denials on international flights.\n\n### 2. Monitor Ticket Issuance Time Limits (TTL)\n\nNever allow an active seat reservation to hit the automated airline TTL. Once a PNR is canceled by the carrier GDS, reinstating the same fare class is virtually impossible on high-occupancy sectors.\n\n### 3. Maintain an Active Working Float\n\nEnsure your EasyTicket B2B balance has adequate buffer so that late-night urgent reservations can be issued instantly without waiting for morning banking hours.\n\n### 4. Provide Written Cancellation Terms to Clients\n\nClearly communicate airline cancellation penalties and service fees prior to collecting client deposits.",
                'image' => '/images/destinations/istanbul.jpg',
                'author_name' => 'EasyTicket Risk & Compliance',
                'read_time' => '3 min read',
                'tags' => ['Operations', 'Agency Tips', 'Risk Management', 'Customer Service'],
                'is_published' => true,
                'is_featured' => false,
                'views_count' => 190,
                'published_at' => Carbon::now()->subDays(14),
            ],
        ];

        foreach ($posts as $post) {
            Blog::updateOrCreate(
                ['slug' => $post['slug']],
                $post
            );
        }
    }
}
