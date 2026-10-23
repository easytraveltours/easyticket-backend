<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Heartbeat
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($user = $request->user()) {
            $user->updateQuietly([
                'last_activity' => now(),
                'is_online' => true,
                'current_page' => $request->header('X-Current-Page', substr($request->path(), 0, 250)),
            ]);
        }

        return $next($request);
    }
}
