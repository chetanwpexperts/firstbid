<?php

namespace App\Http\Controllers;

use App\Services\ProposalAI;
use App\Services\TelegramNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

    /**
     * User-initiated letter generation — the manual counterpart to
     * ProcessIncomingJob's auto-generate path. Does not re-check the
     * user's score/payment filters: those only govern what happens
     * automatically, not what a user is allowed to generate by hand.
     */
    public function generate(Request $request, int $id, ProposalAI $ai, TelegramNotifier $telegram)
    {
        $user = $request->user();
        $job = $user->upworkJobs()->findOrFail($id);

        if ($job->cover_letter) {
            return redirect()->route('jobs.show', $job->id);
        }

        if (! $user->canGenerate()) {
            return back()->withErrors([
                'generate' => 'Your trial has ended — paid plans are coming soon. Contact us to keep generating letters.',
            ]);
        }

        if ($user->quota_reset_at === null || now()->greaterThan($user->quota_reset_at)) {
            $user->forceFill(['letters_used' => 0, 'quota_reset_at' => now()->addMonth()])->save();
        }

        if ($user->letters_used >= $user->letters_quota) {
            return back()->withErrors([
                'generate' => "Monthly letter limit reached ({$user->letters_quota}). Resets on {$user->quota_reset_at?->format('d M')}.",
            ]);
        }

        try {
            $result = $ai->generate($job, $user->proposal_profile ?? '');

            $job->update([
                'cover_letter'     => $result['cover_letter'],
                'question_answers' => $result['question_answers'],
                'bid_suggestion'   => $result['bid_suggestion'],
                'status'           => 'generated',
            ]);

            $user->increment('letters_used');
        } catch (\Throwable $e) {
            Log::error("Manual AI generation failed for job {$job->id}: " . $e->getMessage());

            return back()->withErrors(['generate' => 'Letter generation failed — please try again.']);
        }

        try {
            $telegram->sendJobAlert($job->fresh('user'), $user->telegram_chat_id);
            $job->update(['status' => 'notified']);
        } catch (\Throwable $e) {
            $job->update(['status' => 'failed', 'skip_reason' => 'Telegram: ' . mb_substr($e->getMessage(), 0, 200)]);
            Log::error("Telegram failed for job {$job->id}: " . $e->getMessage());
        }

        return redirect()->route('jobs.show', $job->id)
            ->with('status', 'Cover letter generated!')
            ->with('ok', 'Cover letter generated!');
    }
}
