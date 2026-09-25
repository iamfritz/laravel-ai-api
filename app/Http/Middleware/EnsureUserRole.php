<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles, true)) {
            return response()->json([
                'message' => 'Unauthorized. You do not have permission to perform this action.',
            ], 403);
        }

        return $next($request);
    }
}
