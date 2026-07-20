<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $jobs = $user->upworkJobs()
            ->latest()
            ->when($request->query('status'), fn ($q, $s) => $q->where('status', $s))
            ->paginate(15)
            ->withQueryString();

        $notified = $user->upworkJobs()->where('status', 'notified')->count();

        $stats = [
            'total'    => $user->upworkJobs()->count(),
            'letters'  => $notified,   // key used by dashboard.blade.php
            'notified' => $notified,   // alias, in case any view uses this name
            'skipped'  => $user->upworkJobs()->where('status', 'skipped')->count(),
            'quota'    => max(0, $user->letters_quota - $user->letters_used),
        ];

        $view = View::exists('dashboard') ? 'dashboard' : 'dashboard.index';

        return view($view, compact('jobs', 'stats', 'user'));
    }

    public function show(Request $request, int $id)
    {
        $job = $request->user()->upworkJobs()->findOrFail($id);

        $view = View::exists('dashboard.show') ? 'dashboard.show' : 'job';

        return view($view, compact('job'));
    }
}
