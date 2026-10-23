<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Export reports as CSV stream.
     */
    public function export(Request $request): StreamedResponse
    {
        $type = $request->input('type', 'sales');
        $filename = "easyticket-{$type}-" . date('Y-m-d') . ".csv";

        return response()->streamDownload(function () use ($type) {
            $handle = fopen('php://output', 'w');

            if ($type === 'sales') {
                fputcsv($handle, ['Booking Code', 'Date', 'Agency', 'Agent Email', 'Sector', 'Flight', 'PNR', 'eTicket', 'Seats', 'Amount (PKR)', 'Status']);

                Booking::with(['ticket', 'agent'])
                    ->latest()
                    ->chunk(200, function ($bookings) use ($handle) {
                        foreach ($bookings as $b) {
                            fputcsv($handle, [
                                $b->booking_code,
                                $b->created_at->format('Y-m-d H:i'),
                                $b->agent->company_name ?? $b->agent->name ?? '—',
                                $b->agent->email ?? '—',
                                ($b->ticket->dep_city ?? '') . ' → ' . ($b->ticket->arr_city ?? ''),
                                ($b->ticket->airline_name ?? '') . ' ' . ($b->ticket->flight_number ?? ''),
                                $b->pnr_issued ?: ($b->ticket->pnr ?? '—'),
                                $b->e_ticket_number ?: '—',
                                $b->seats_count,
                                $b->total_amount,
                                $b->status,
                            ]);
                        }
                    });
            } elseif ($type === 'inventory') {
                fputcsv($handle, ['Ticket Code', 'PNR', 'Airline', 'Flight No', 'From', 'To', 'Dep Date', 'Dep Time', 'Cabin', 'Public Price', 'B2B Price', 'Available', 'Reserved', 'Active', 'Status']);

                Ticket::latest()->chunk(200, function ($tickets) use ($handle) {
                    foreach ($tickets as $t) {
                        fputcsv($handle, [
                            $t->ticket_code,
                            $t->pnr,
                            $t->airline_name,
                            $t->flight_number,
                            $t->dep_city . ' (' . $t->dep_airport_code . ')',
                            $t->arr_city . ' (' . $t->arr_airport_code . ')',
                            $t->dep_date,
                            $t->dep_time,
                            $t->cabin_class,
                            $t->public_price,
                            $t->b2b_price,
                            $t->available_inventory,
                            $t->reserved_inventory,
                            $t->active_inventory,
                            $t->status,
                        ]);
                    }
                });
            } elseif ($type === 'agents') {
                fputcsv($handle, ['Agency Name', 'Contact Person', 'Email', 'Phone', 'City', 'Tier Group', 'Status', 'Wallet Balance', 'Credit Limit', 'Total Bookings', 'Joined Date']);

                User::with('group')
                    ->where('role', 'agent')
                    ->withCount('bookings')
                    ->latest()
                    ->chunk(200, function ($agents) use ($handle) {
                        foreach ($agents as $a) {
                            fputcsv($handle, [
                                $a->company_name ?: $a->name,
                                $a->name,
                                $a->email,
                                $a->phone,
                                $a->city,
                                $a->group->name ?? 'Standard',
                                $a->status,
                                $a->wallet_balance,
                                $a->credit_limit,
                                $a->bookings_count,
                                $a->created_at->format('Y-m-d'),
                            ]);
                        }
                    });
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
