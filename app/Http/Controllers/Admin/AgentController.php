<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WalletAdjustRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\ActivityLogService;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{
    /**
     * List agents with status filtering and search.
     */
    public function index(Request $request): JsonResponse
    {
        $status = $request->input('status', 'all');
        $search = $request->input('search', '');

        $query = User::with('group')->where('role', 'agent');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $agents = $query->latest()->paginate(20);

        return response()->json([
            'data' => UserResource::collection($agents),
            'pagination' => [
                'current_page' => $agents->currentPage(),
                'last_page' => $agents->lastPage(),
                'total' => $agents->total(),
            ],
        ]);
    }

    /**
     * Show single agent details.
     */
    public function show(int $id): JsonResponse
    {
        $agent = User::with(['group', 'bookings.ticket', 'walletTransactions' => fn ($q) => $q->latest()->take(10)])
            ->where('role', 'agent')
            ->findOrFail($id);

        return response()->json([
            'data' => new UserResource($agent),
            'wallet_transactions' => $agent->walletTransactions,
        ]);
    }

    /**
     * Approve pending agent.
     */
    public function approve(Request $request, int $id): JsonResponse
    {
        $admin = $request->user();
        $agent = User::where('role', 'agent')->findOrFail($id);

        $agent->update([
            'status' => 'active',
            'group_id' => $request->input('group_id', $agent->group_id),
            'credit_limit' => $request->input('credit_limit', $agent->credit_limit),
        ]);

        NotificationService::notify(
            $agent->id,
            'Agency Account Approved!',
            'Congratulations! Your agency account has been verified and activated. You can now access exclusive wholesale B2B flight inventory.',
            'SUCCESS',
            '/agent/dashboard'
        );

        ActivityLogService::log(
            $admin,
            'AGENT_APPROVED',
            'USER',
            (string) $agent->id,
            "Approved agency {$agent->company_name} ({$agent->email})"
        );

        return response()->json([
            'message' => "Agency {$agent->company_name} has been approved and activated.",
            'data' => new UserResource($agent->fresh('group')),
        ]);
    }

    /**
     * Reject agent application.
     */
    public function reject(Request $request, int $id): JsonResponse
    {
        $admin = $request->user();
        $agent = User::where('role', 'agent')->findOrFail($id);
        $reason = $request->input('reason', 'Application did not meet requirements.');

        $agent->update(['status' => 'rejected']);

        NotificationService::notify(
            $agent->id,
            'Agency Application Update',
            "Your application has been reviewed: {$reason}",
            'DANGER'
        );

        ActivityLogService::log(
            $admin,
            'AGENT_REJECTED',
            'USER',
            (string) $agent->id,
            "Rejected agency {$agent->company_name}. Reason: {$reason}"
        );

        return response()->json([
            'message' => "Agency application for {$agent->company_name} was rejected.",
            'data' => new UserResource($agent),
        ]);
    }

    /**
     * Suspend active agent.
     */
    public function suspend(Request $request, int $id): JsonResponse
    {
        $admin = $request->user();
        $agent = User::where('role', 'agent')->findOrFail($id);
        $reason = $request->input('reason', 'Account suspended by operations administrator.');

        $agent->update(['status' => 'suspended']);

        // Revoke active tokens
        $agent->tokens()->delete();

        NotificationService::notify(
            $agent->id,
            'Account Suspended',
            "Your agency account has been suspended: {$reason}",
            'DANGER'
        );

        ActivityLogService::log(
            $admin,
            'AGENT_SUSPENDED',
            'USER',
            (string) $agent->id,
            "Suspended agency {$agent->company_name}. Reason: {$reason}"
        );

        return response()->json([
            'message' => "Agency {$agent->company_name} has been suspended.",
            'data' => new UserResource($agent),
        ]);
    }

    /**
     * Reactivate suspended agent.
     */
    public function reactivate(Request $request, int $id): JsonResponse
    {
        $admin = $request->user();
        $agent = User::where('role', 'agent')->findOrFail($id);

        $agent->update(['status' => 'active']);

        NotificationService::notify(
            $agent->id,
            'Account Reactivated',
            'Your agency account has been reactivated. You can now log in and book tickets.',
            'SUCCESS',
            '/agent/dashboard'
        );

        ActivityLogService::log(
            $admin,
            'AGENT_REACTIVATED',
            'USER',
            (string) $agent->id,
            "Reactivated agency {$agent->company_name}"
        );

        return response()->json([
            'message' => "Agency {$agent->company_name} has been reactivated.",
            'data' => new UserResource($agent),
        ]);
    }

    /**
     * Adjust agent wallet balance.
     */
    public function adjustWallet(WalletAdjustRequest $request, int $id): JsonResponse
    {
        $admin = $request->user();
        $agent = User::where('role', 'agent')->findOrFail($id);
        $validated = $request->validated();

        $amount = (float) $validated['amount'];
        $type = $validated['type'];
        $notes = $validated['notes'] ?? '';

        $newBalance = DB::transaction(function () use ($agent, $type, $amount, $notes, $validated, $admin) {
            $currentBalance = (float) $agent->wallet_balance;

            $updatedBalance = match ($type) {
                'DEPOSIT', 'CREDIT', 'REFUND', 'COMMISSION' => $currentBalance + $amount,
                'DEBIT' => max(0, $currentBalance - $amount),
            };

            $agent->update(['wallet_balance' => $updatedBalance]);

            WalletTransaction::create([
                'agent_id' => $agent->id,
                'type' => $type,
                'amount' => $amount,
                'balance_after' => $updatedBalance,
                'reference_id' => $validated['reference_id'] ?? 'ADJ-' . time(),
                'notes' => $notes ?: "Manual adjustment by Admin {$admin->name}",
            ]);

            NotificationService::notify(
                $agent->id,
                "Wallet {$type}: PKR {$amount}",
                "Your wallet balance was updated. Current balance: PKR " . number_format($updatedBalance, 2),
                'INFO',
                '/agent/dashboard'
            );

            ActivityLogService::log(
                $admin,
                'WALLET_ADJUSTED',
                'USER',
                (string) $agent->id,
                "Wallet {$type} PKR {$amount} for {$agent->company_name}. New Balance: PKR {$updatedBalance}"
            );

            return $updatedBalance;
        });

        return response()->json([
            'message' => "Wallet balance updated to PKR " . number_format($newBalance, 2),
            'wallet_balance' => $newBalance,
        ]);
    }
}
