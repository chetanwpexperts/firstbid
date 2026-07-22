<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->is_approved) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Your account is pending admin approval.'], 403);
            }

            return redirect()->route('pending');
        }

        return $next($request);
    }
}
