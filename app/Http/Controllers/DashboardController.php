<?php

namespace App\Http\Controllers;

use App\Helpers\HashId;
use App\Services\ProposalAI;
use App\Services\TelegramNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function index(Request $request, ?int $page = null)
    {
        $user = $request->user();

        // Redirect query string ?page=X or ?window=X to clean RESTful path
        if ($request->has('window') || $request->has('page')) {
            $queryPage = (int) $request->query('page', 1);
            if ($queryPage > 1) {
                return redirect()->route('dashboard.page', ['page' => $queryPage]);
            }
            return redirect()->route('dashboard');
        }

        if ($page !== null) {
            if ($page <= 1) {
                return redirect()->route('dashboard');
            }
            $request->merge(['page' => $page]);
        }

        $window = '24h';
        $windowStart = now()->subHours(24);

        // Fresh builder per call — only return payment verified jobs from the last 24 hours
        $windowed = fn () => $user->upworkJobs()
            ->where('created_at', '>=', $windowStart)
            ->where('payment_verified', true);

        $jobs = $windowed()
            ->latest()
            ->when($request->query('status'), fn ($q, $s) => $q->where('status', $s))
            ->paginate(15);

        $notified = $windowed()->where('status', 'notified')->count();

        $stats = [
            'total'    => $windowed()->count(),
            'letters'  => $notified,
            'notified' => $notified,
            'skipped'  => $windowed()->where('status', 'skipped')->count(),
            'quota'    => max(0, $user->letters_quota - $user->letters_used),
        ];

        $user->update(['last_seen_jobs_at' => now()]);

        $view = View::exists('dashboard') ? 'dashboard' : 'dashboard.index';

        return view($view, compact('jobs', 'stats', 'user', 'window'));
    }

    public function show(Request $request, string|int $id)
    {
        $realId = HashId::decode($id);
        if (!$realId) {
            abort(404);
        }

        $job = $request->user()->upworkJobs()->findOrFail($realId);

        $view = View::exists('dashboard.show') ? 'dashboard.show' : 'job';

        return view($view, compact('job'));
    }

    public function generate(Request $request, string|int $id, ProposalAI $ai, TelegramNotifier $telegram)
    {
        $realId = HashId::decode($id);
        if (!$realId) {
            abort(404);
        }

        $user = $request->user();
        $job = $user->upworkJobs()->findOrFail($realId);

        if ($job->cover_letter) {
            return redirect()->route('jobs.show', $job);
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
            $result = $ai->generate($job, $user->proposal_profile ?? '', $user->portfolio_projects ?? []);

            $job->update([
                'cover_letter'       => $result['cover_letter'],
                'opener_hooks'       => $result['opener_hooks'] ?? null,
                'milestones'         => $result['milestones'] ?? null,
                'matched_portfolio'  => $result['matched_portfolio'] ?? null,
                'question_answers'   => $result['question_answers'],
                'bid_suggestion'     => $result['bid_suggestion'],
                'estimated_budget'   => $result['estimated_budget'] ?? null,
                'estimated_duration' => $result['estimated_duration'] ?? null,
                'budget_reasoning'  => $result['budget_reasoning'] ?? null,
                'task_breakdown'     => $result['task_breakdown'] ?? null,
                'status'             => 'generated',
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

        return redirect()->route('jobs.show', $job)
            ->with('status', 'Cover letter generated!')
            ->with('ok', 'Cover letter generated!');
    }
}
