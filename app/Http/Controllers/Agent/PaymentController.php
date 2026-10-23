<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\SubmitPaymentRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\PaymentMethodResource;
use App\Models\Booking;
use App\Models\PaymentMethod;
use App\Services\ActivityLogService;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * List active payment methods (corporate bank accounts).
     */
    public function methods(): JsonResponse
    {
        $methods = PaymentMethod::where('is_active', true)
            ->orderBy('display_order', 'asc')
            ->get();

        return response()->json([
            'data' => PaymentMethodResource::collection($methods),
        ]);
    }

    /**
     * Submit payment proof.
     */
    public function submit(SubmitPaymentRequest $request): JsonResponse
    {
        $agent = $request->user();
        $validated = $request->validated();

        $booking = Booking::with('ticket')
            ->where('booking_code', $validated['booking_code'])
            ->where('agent_id', $agent->id)
            ->firstOrFail();

        if ($booking->status === 'ACTIVE') {
            return response()->json([
                'message' => 'This booking has already been activated and verified.',
            ], 422);
        }

        $booking->update([
            'payment_method' => $validated['payment_method'],
            'payment_tx_id' => $validated['transaction_id'],
            'payment_amount' => $validated['amount'],
            'payment_proof_notes' => $validated['notes'] ?? null,
            'payment_submitted_at' => now(),
            'status' => 'PAYMENT_VERIFICATION',
        ]);

        NotificationService::notify(
            $agent->id,
            'Payment Submitted for Verification',
            "Your payment proof for {$booking->booking_code} (TxID: {$validated['transaction_id']}) is received. Operations will verify and activate your flight shortly.",
            'INFO',
            "/agent/tickets/{$booking->booking_code}"
        );

        NotificationService::notifyAdmins(
            'New Payment Proof Received',
            "Agent {$agent->company_name} submitted payment for {$booking->booking_code} (PKR {$validated['amount']}). Please verify and issue PNR.",
            'INFO',
            '/admin/payments'
        );

        ActivityLogService::log(
            $agent,
            'PAYMENT_SUBMITTED',
            'BOOKING',
            $booking->booking_code,
            "Submitted payment proof: {$validated['payment_method']}, TxID: {$validated['transaction_id']}, Amount: PKR {$validated['amount']}"
        );

        return response()->json([
            'message' => 'Payment proof submitted successfully! Admin will verify and activate your ticket.',
            'data' => new BookingResource($booking->fresh('ticket')),
        ]);
    }
}
