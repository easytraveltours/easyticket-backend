<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VerifyPaymentRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Ticket;
use App\Services\ActivityLogService;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * List payment verifications queue.
     */
    public function index(Request $request): JsonResponse
    {
        $filter = $request->input('filter', 'PAYMENT_VERIFICATION');

        $query = Booking::with(['ticket', 'agent']);

        if ($filter === 'PAYMENT_VERIFICATION') {
            $query->whereIn('status', ['PAYMENT_VERIFICATION', 'RESERVED']);
        } elseif ($filter !== 'ALL') {
            $query->where('status', $filter);
        }

        $payments = $query->latest('payment_submitted_at')->latest()->paginate(20);

        return response()->json([
            'data' => BookingResource::collection($payments),
            'pagination' => [
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
                'total' => $payments->total(),
            ],
        ]);
    }

    /**
     * Verify payment: Approve or Reject.
     */
    public function verify(VerifyPaymentRequest $request, int $id): JsonResponse
    {
        $admin = $request->user();
        $validated = $request->validated();
        $booking = Booking::with(['ticket', 'agent'])->findOrFail($id);

        $action = $validated['action'];
        $notes = $validated['notes'] ?? '';

        if ($action === 'APPROVE') {
            DB::transaction(function () use ($booking, $validated, $admin, $notes) {
                $confirmedPnr = ! empty($validated['pnr']) ? strtoupper(trim($validated['pnr'])) : ($booking->ticket->pnr ?? strtoupper(fake()->bothify('??####')));
                $eTicket = 'ETK-' . rand(100000000, 999999999);
                $seats = (int) ($booking->seats_count ?: 1);

                $booking->update([
                    'status' => 'ACTIVE',
                    'verified_at' => now(),
                    'activated_at' => now(),
                    'verified_by_admin_id' => $admin->id,
                    'admin_notes' => $notes,
                    'pnr_issued' => $confirmedPnr,
                    'e_ticket_number' => $eTicket,
                ]);

                // Move seats from reserved to active
                if ($booking->ticket_id) {
                    Ticket::where('id', $booking->ticket_id)
                        ->update([
                            'reserved_inventory' => DB::raw("CASE WHEN reserved_inventory >= {$seats} THEN reserved_inventory - {$seats} ELSE 0 END"),
                            'active_inventory' => DB::raw("active_inventory + {$seats}"),
                        ]);
                }

                NotificationService::notify(
                    $booking->agent_id,
                    "Ticket Activated! PNR: {$confirmedPnr}",
                    "Your booking {$booking->booking_code} is now ACTIVE! e-Ticket #{$eTicket}. Download from My Tickets.",
                    'SUCCESS',
                    "/agent/tickets/{$booking->booking_code}"
                );

                NotificationService::sendMessage(
                    sender: $admin,
                    receiver: $booking->agent,
                    subject: "Your {$booking->ticket->arr_city} Flight Ticket is Now Active",
                    body: "Dear {$booking->agent->name},\n\nPayment for {$booking->booking_code} ({$booking->ticket->airline_name} {$booking->ticket->flight_number}) has been verified.\nPNR: {$confirmedPnr}\nTicket #: {$eTicket}\n\nYour e-ticket is available in the Agent Portal.\n\nEasyTicket Operations",
                    type: 'TICKET_ACTIVATED',
                    bookingId: $booking->id
                );

                ActivityLogService::log(
                    $admin,
                    'PAYMENT_APPROVED',
                    'BOOKING',
                    $booking->booking_code,
                    "Payment approved. PNR issued: {$confirmedPnr}, eTicket: {$eTicket}"
                );
            });

            return response()->json([
                'message' => "Booking {$booking->booking_code} approved and e-Ticket issued successfully.",
                'data' => new BookingResource($booking->fresh(['ticket', 'agent'])),
            ]);
        }

        // Action is REJECT
        DB::transaction(function () use ($booking, $notes, $admin) {
            $seats = (int) ($booking->seats_count ?: 1);

            $booking->update([
                'status' => 'REJECTED',
                'verified_at' => now(),
                'verified_by_admin_id' => $admin->id,
                'admin_notes' => $notes,
            ]);

            // Release reserved seats back to available
            if ($booking->ticket_id) {
                Ticket::where('id', $booking->ticket_id)
                    ->update([
                        'available_inventory' => DB::raw("available_inventory + {$seats}"),
                        'reserved_inventory' => DB::raw("CASE WHEN reserved_inventory >= {$seats} THEN reserved_inventory - {$seats} ELSE 0 END"),
                    ]);
            }

            NotificationService::notify(
                $booking->agent_id,
                'Payment Proof Rejected',
                "Payment proof for {$booking->booking_code} was rejected: {$notes}. The held seat(s) have been released.",
                'DANGER',
                '/agent/payments'
            );

            ActivityLogService::log(
                $admin,
                'PAYMENT_REJECTED',
                'BOOKING',
                $booking->booking_code,
                "Payment rejected for {$booking->booking_code}. Reason: {$notes}"
            );
        });

        return response()->json([
            'message' => "Booking {$booking->booking_code} payment rejected and seats released.",
            'data' => new BookingResource($booking->fresh(['ticket', 'agent'])),
        ]);
    }
}
