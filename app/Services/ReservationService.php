<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\SiteSetting;
use App\Models\Ticket;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    /**
     * Atomically reserve seats for a ticket.
     */
    public static function reserve(
        User $agent,
        int $ticketId,
        int $seats,
        array $passengerData
    ): array {
        // First expire any timed out holds
        InventoryService::expireOverdueReservations();

        $expiryMinutes = (int) (SiteSetting::get('reservation_expiry_minutes', '30') ?: 30);
        $expiresAt = now()->addMinutes($expiryMinutes);

        return DB::transaction(function () use ($agent, $ticketId, $seats, $passengerData, $expiryMinutes, $expiresAt) {
            // Atomic conditional update
            $updated = Ticket::where('id', $ticketId)
                ->where('available_inventory', '>=', $seats)
                ->update([
                    'available_inventory' => DB::raw("available_inventory - {$seats}"),
                    'reserved_inventory' => DB::raw("reserved_inventory + {$seats}"),
                ]);

            if ($updated === 0) {
                throw new Exception('Sorry, those seats are no longer available (another agent just reserved them).');
            }

            $ticket = Ticket::findOrFail($ticketId);

            // Compute agent group discount
            $unitPrice = (float) $ticket->b2b_price;
            if ($agent->group_id && $agent->group) {
                $discount = (float) $agent->group->discount_percent;
                if ($discount > 0) {
                    $unitPrice = round($unitPrice * (1 - $discount / 100));
                }
            }

            $totalAmount = $unitPrice * $seats;
            $bookingCode = 'ETB-' . date('Y') . '-' . rand(100000, 999999);

            $booking = Booking::create([
                'booking_code' => $bookingCode,
                'ticket_id' => $ticket->id,
                'agent_id' => $agent->id,
                'seats_count' => $seats,
                'passenger_title' => $passengerData['passenger_title'] ?? 'Mr',
                'passenger_first_name' => $passengerData['passenger_first_name'],
                'passenger_last_name' => $passengerData['passenger_last_name'],
                'passenger_passport' => $passengerData['passenger_passport'],
                'passenger_nationality' => $passengerData['passenger_nationality'] ?? 'Pakistani',
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'currency' => 'PKR',
                'status' => 'RESERVED',
                'expires_at' => $expiresAt,
            ]);

            // Notifications
            NotificationService::notify(
                $agent->id,
                'Ticket Reserved Successfully',
                "Temporary reservation {$bookingCode} created for {$ticket->dep_city} → {$ticket->arr_city}. You have {$expiryMinutes} minutes to submit payment proof.",
                'BOOKING',
                "/agent/payments?bookingCode={$bookingCode}"
            );

            NotificationService::notifyAdmins(
                'New Ticket Reserved',
                "Agent {$agent->company_name} ({$agent->name}) reserved {$ticket->dep_city} → {$ticket->arr_city} ({$bookingCode}). Remaining: {$ticket->available_inventory}",
                'INFO',
                "/admin/bookings"
            );

            ActivityLogService::log(
                $agent,
                'TICKET_RESERVED',
                'BOOKING',
                $bookingCode,
                "Reserved {$seats} seat(s) on {$ticket->airline_name} {$ticket->flight_number}. Expires {$expiresAt->toDateTimeString()}."
            );

            return [
                'booking' => $booking,
                'booking_code' => $bookingCode,
                'route' => "{$ticket->dep_city} ({$ticket->dep_airport_code}) → {$ticket->arr_city} ({$ticket->arr_airport_code})",
                'airline' => $ticket->airline_name,
                'flight_number' => $ticket->flight_number,
                'seats_count' => $seats,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'expires_at' => $expiresAt->toIso8601String(),
                'expiry_minutes' => $expiryMinutes,
            ];
        });
    }
}
