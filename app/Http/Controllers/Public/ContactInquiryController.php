<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactInquiryController extends Controller
{
    /**
     * Store a newly submitted contact inquiry.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'phone'   => 'required|string|max:50',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:3000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $inquiry = ContactInquiry::create([
            'name'       => trim($request->input('name')),
            'email'      => trim(strtolower($request->input('email'))),
            'phone'      => trim($request->input('phone')),
            'subject'    => trim($request->input('subject')) ?: 'General Inquiry',
            'message'    => trim($request->input('message')),
            'status'     => 'new',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for reaching out! Your message has been received and our operations desk will contact you shortly.',
            'inquiry' => [
                'id'         => $inquiry->id,
                'created_at' => $inquiry->created_at,
            ],
        ], 201);
    }
}

