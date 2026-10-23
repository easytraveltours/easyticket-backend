<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveAgent
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isAgent()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Travel Agent portal access only.',
            ], 403);
        }

        if ($user->status === 'pending') {
            return response()->json([
                'success' => false,
                'status' => 'pending',
                'message' => 'Your agency account registration is pending review by EasyTicket operations. You will receive an email once approved.',
            ], 403);
        }

        if ($user->status === 'suspended') {
            return response()->json([
                'success' => false,
                'status' => 'suspended',
                'message' => 'Your agency account has been suspended. Please contact operations support.',
            ], 403);
        }

        if ($user->status === 'rejected') {
            return response()->json([
                'success' => false,
                'status' => 'rejected',
                'message' => 'Your agency registration was not approved. Contact support for re-evaluation.',
            ], 403);
        }

        return $next($request);
    }
}
