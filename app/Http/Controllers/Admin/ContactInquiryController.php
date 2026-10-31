<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactInquiryController extends Controller
{
    /**
     * List all contact inquiries with filters and counts.
     */
    public function index(Request $request): JsonResponse
    {
        $query = ContactInquiry::query();

        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $inquiries = $query->latest()->paginate(25);

        $counts = [
            'all'       => ContactInquiry::count(),
            'new'       => ContactInquiry::where('status', 'new')->count(),
            'contacted' => ContactInquiry::where('status', 'contacted')->count(),
            'resolved'  => ContactInquiry::where('status', 'resolved')->count(),
            'closed'    => ContactInquiry::where('status', 'closed')->count(),
        ];

        return response()->json([
            'data'   => $inquiries->items(),
            'meta'   => [
                'current_page' => $inquiries->currentPage(),
                'last_page'    => $inquiries->lastPage(),
                'per_page'     => $inquiries->perPage(),
                'total'        => $inquiries->total(),
            ],
            'counts' => $counts,
        ]);
    }

    /**
     * Display a specific inquiry.
     */
    public function show(int $id): JsonResponse
    {
        $inquiry = ContactInquiry::findOrFail($id);

        return response()->json([
            'data' => $inquiry,
        ]);
    }

    /**
     * Update status and/or admin notes for an inquiry.
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $inquiry = ContactInquiry::findOrFail($id);

        $validated = $request->validate([
            'status'      => 'sometimes|required|string|in:new,contacted,resolved,closed',
            'admin_notes' => 'nullable|string|max:5000',
        ]);

        $inquiry->update($validated);

        return response()->json([
            'message' => 'Inquiry updated successfully.',
            'data'    => $inquiry,
        ]);
    }

    /**
     * Delete an inquiry.
     */
    public function destroy(int $id): JsonResponse
    {
        $inquiry = ContactInquiry::findOrFail($id);
        $inquiry->delete();

        return response()->json([
            'message' => 'Inquiry deleted successfully.',
        ]);
    }
}

