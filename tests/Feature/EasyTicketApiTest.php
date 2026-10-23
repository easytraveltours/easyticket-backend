<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\AgentGroupSeeder;
use Database\Seeders\AirlineSeeder;
use Database\Seeders\AirportSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\PaymentMethodSeeder;
use Database\Seeders\SiteSettingSeeder;
use Database\Seeders\TicketSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EasyTicketApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
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

    public function test_public_inventory_stats(): void
    {
        $response = $this->getJson('/api/inventory/stats');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'available_seats',
                'active_routes',
                'partner_airlines',
                'global_destinations',
                'verified_agents',
            ]);
    }

    public function test_public_airport_search(): void
    {
        $response = $this->getJson('/api/airports/search?q=ISB');
        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function test_admin_login(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'admin@easyticket.com.pk',
            'password' => 'admin123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user',
                'role',
            ]);
    }

    public function test_agent_login(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'agent@almadina.pk',
            'password' => 'agent123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user',
                'role',
            ]);
    }

    public function test_flight_search_and_materialization(): void
    {
        $response = $this->getJson('/api/flights?from=ISB&to=DXB&date=' . date('Y-m-d'));
        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'count']);
        $this->assertGreaterThan(0, $response->json('count'));
    }

    public function test_agent_reservation_and_payment_flow(): void
    {
        $agent = User::where('email', 'agent@almadina.pk')->first();
        $ticket = Ticket::where('available_inventory', '>', 2)->first();

        // 1. Agent reserves seats
        $resResponse = $this->actingAs($agent, 'sanctum')->postJson('/api/agent/reserve', [
            'ticket_id' => $ticket->id,
            'seats' => 2,
            'passenger_title' => 'Mr',
            'passenger_first_name' => 'Muhammad',
            'passenger_last_name' => 'Ali',
            'passenger_passport' => 'PA9876543',
            'passenger_nationality' => 'Pakistani',
        ]);

        $resResponse->assertStatus(201);
        $bookingCode = $resResponse->json('data.booking_code');
        $this->assertNotEmpty($bookingCode);

        // 2. Agent submits payment proof
        $payResponse = $this->actingAs($agent, 'sanctum')->postJson('/api/agent/payments', [
            'booking_code' => $bookingCode,
            'payment_method' => 'Habib Bank Limited (HBL)',
            'transaction_id' => 'TXN-99881122',
            'amount' => $resResponse->json('data.total_amount'),
            'notes' => 'Online IBFT transfer receipt attached',
        ]);

        $payResponse->assertStatus(200);

        // 3. Admin verifies and approves payment
        $admin = User::where('email', 'admin@easyticket.com.pk')->first();
        $bookingId = $payResponse->json('data.id');

        $approveResponse = $this->actingAs($admin, 'sanctum')->postJson("/api/admin/payments/{$bookingId}/verify", [
            'action' => 'APPROVE',
            'pnr' => 'PK786A',
            'notes' => 'Funds credited to HBL corporate account.',
        ]);

        $approveResponse->assertStatus(200)
            ->assertJsonPath('data.status', 'ACTIVE')
            ->assertJsonPath('data.pnr_issued', 'PK786A');
    }
}
