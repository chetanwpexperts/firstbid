<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Called by the bell dropdown's JS when it's opened — marks
     * everything up to now as seen, so the badge count resets.
     */
    public function markSeen(Request $request)
    {
        $request->user()->update(['last_seen_jobs_at' => now()]);

        return response()->json(['ok' => true]);
    }
}
