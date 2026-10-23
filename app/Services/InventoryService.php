<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Materialize worldwide offers into the tickets table.
     */
    public static function materializeWorldwide(
        array $origin,
        array $dest,
        string $date,
        ?string $cabin = 'Economy'
    ): void {
        $offers = FlightSearchService::generateWorldwideOffers($origin, $dest, $date, $cabin);

        foreach ($offers as $o) {
            $ticketCode = 'WW-' . $o['originCode'] . '-' . $o['destCode'] . '-' . $o['airlineCode']
                . str_replace(' ', '', $o['flightNumber']) . '-' . $date;

            $exists = Ticket::where('ticket_code', $ticketCode)->exists();
            if ($exists) {
                continue;
            }

            Ticket::create([
                'ticket_code' => $ticketCode,
                'pnr' => $o['airlineCode'] . rand(1000, 9999),
                'airline_name' => $o['airline'],
                'flight_number' => $o['flightNumber'],
                'dep_airport_code' => $o['originCode'],
                'dep_airport_name' => $o['originAirport'],
                'dep_city' => $o['originCity'],
                'dep_country' => $o['originCountry'],
                'dep_date' => $o['departureDate'],
                'dep_time' => $o['departureTime'],
                'arr_airport_code' => $o['destCode'],
                'arr_airport_name' => $o['destAirport'],
                'arr_city' => $o['destCity'],
                'arr_country' => $o['destCountry'],
                'arr_date' => $o['arrivalDate'],
                'arr_time' => $o['arrivalTime'],
                'duration' => $o['duration'],
                'stops' => $o['stops'],
                'cabin_class' => $o['cabinClass'],
                'baggage' => $o['baggage'],
                'is_refundable' => $o['isRefundable'],
                'public_price' => $o['publicPrice'],
                'b2b_price' => $o['b2bPrice'],
                'currency' => 'PKR',
                'total_inventory' => $o['availableSeats'],
                'available_inventory' => $o['availableSeats'],
                'reserved_inventory' => 0,
                'active_inventory' => 0,
                'status' => 'AVAILABLE',
                'low_stock_threshold' => 5,
                'expiry_date' => $date,
            ]);
        }
    }

    /**
     * Expire overdue reservations and release seats back to inventory.
     */
    public static function expireOverdueReservations(): int
    {
        $now = now();
        $expiredBookings = Booking::with('ticket')
            ->where('status', 'RESERVED')
            ->where('expires_at', '<', $now)
            ->get();

        $count = 0;

        foreach ($expiredBookings as $booking) {
            DB::transaction(function () use ($booking) {
                // Update booking status
                $booking->update([
                    'status' => 'EXPIRED',
                ]);

                // Restore seats to ticket
                $seats = $booking->seats_count ?: 1;
                if ($booking->ticket_id) {
                    Ticket::where('id', $booking->ticket_id)
                        ->update([
                            'available_inventory' => DB::raw("available_inventory + {$seats}"),
                            'reserved_inventory' => DB::raw("CASE WHEN reserved_inventory >= {$seats} THEN reserved_inventory - {$seats} ELSE 0 END"),
                        ]);
                }

                // Notify agent
                NotificationService::notify(
                    $booking->agent_id,
                    'Reservation Expired',
                    "Your booking {$booking->booking_code} has expired. The {$seats} held seat(s) have been returned to available inventory.",
                    'DANGER',
                    '/agent/reservations'
                );

                // Audit log
                ActivityLogService::log(
                    null,
                    'RESERVATION_EXPIRED',
                    'BOOKING',
                    $booking->booking_code,
                    "Automatic hold expiration. {$seats} seat(s) released back to inventory."
                );
            });

            $count++;
        }

        return $count;
    }
}
