<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessIncomingJob;
use App\Models\UpworkJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UpworkWebhookController extends Controller
{
    /**
     * Multi-user endpoint: /api/hook/{token}
     * Each user pastes their personal URL into UpHunt/Vibeworker.
     * Must return 2xx fast — store + queue only.
     */
    public function handle(Request $request, string $token)
    {
        $user = User::where('webhook_token', $token)->first();

        if (! $user) {
            return response()->json(['error' => 'unknown token'], 404);
        }

        $p = $request->all();
        Log::info("Webhook for user {$user->id}", ['title' => $p['title'] ?? null]);

        if (($p['platform'] ?? 'upwork') !== 'upwork') {
            return response()->json(['skipped' => 'platform']);
        }

        $ciphertext = $p['ciphertext'] ?? null;

        // Dedup per user (two users may legitimately receive the same job)
        if ($ciphertext && UpworkJob::where('user_id', $user->id)->where('ciphertext', $ciphertext)->exists()) {
            return response()->json(['skipped' => 'duplicate']);
        }

        $budget = $p['budget'] ?? [];
        $budgetDisplay = ($p['jobType'] ?? '') === 'HOURLY'
            ? sprintf('$%s-%s/hr', $budget['hourlyMin'] ?? '?', $budget['hourlyMax'] ?? '?')
            : (isset($budget['fixedPrice']) ? '$' . $budget['fixedPrice'] . ' fixed' : 'Budget not stated');

        // Real-time webhook is a Pro feature once the trial ends (email source keeps working under normal trial rules).
        $proGated = $user->plan !== 'pro' && ! $user->onTrial();

        $job = UpworkJob::create([
            'user_id'             => $user->id,
            'source'              => 'webhook',
            'ciphertext'          => $ciphertext,
            'title'               => $p['title'] ?? 'Untitled',
            'description'         => $p['description'] ?? '',
            'job_url'             => $p['jobUrl'] ?? null,
            'job_type'            => $p['jobType'] ?? null,
            'budget_display'      => $budgetDisplay,
            'contractor_tier'     => $p['contractorTier'] ?? null,
            'client_country'      => data_get($p, 'client.location.country'),
            'client_score'        => data_get($p, 'client.stats.score'),
            'client_hires'        => data_get($p, 'client.stats.totalAssignments'),
            'payment_verified'    => (bool) data_get($p, 'client.paymentMethodVerified', false),
            'uphunt_score'        => $p['matchingScore'] ?? null,
            'screening_questions' => $p['screeningQuestions'] ?? [],
            'raw_payload'         => $p,
            'status'              => $proGated ? 'skipped' : 'received',
            'skip_reason'         => $proGated ? 'webhook is a Pro feature' : null,
        ]);

        if ($proGated) {
            return response()->json(['ok' => true, 'id' => $job->id]);
        }

        ProcessIncomingJob::dispatch($job->id);

        return response()->json(['ok' => true, 'id' => $job->id]);
    }

    /**
     * Endpoint for Chrome Extension to verify applied status from Upwork: /api/jobs/sync-applied
     */
    public function syncApplied(Request $request)
    {
        $token = $request->header('X-Webhook-Token') ?? $request->input('token');
        $user = User::where('webhook_token', $token)->first();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized token'], 401);
        }

        $ciphertext = $request->input('ciphertext');
        $jobUrl = $request->input('job_url');

        $job = UpworkJob::where('user_id', $user->id)
            ->where(function ($q) use ($ciphertext, $jobUrl) {
                if ($ciphertext) $q->where('ciphertext', $ciphertext);
                if ($jobUrl) $q->orWhere('job_url', $jobUrl);
            })
            ->first();

        if ($job) {
            $job->update([
                'status'     => 'applied',
                'applied_at' => now(),
            ]);

            return response()->json(['success' => true, 'id' => $job->id, 'status' => 'applied']);
        }

        return response()->json(['error' => 'Job not found'], 404);
    }
}
