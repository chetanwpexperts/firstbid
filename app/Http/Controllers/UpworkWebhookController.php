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

        $job = UpworkJob::create([
            'user_id'             => $user->id,
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
        ]);

        ProcessIncomingJob::dispatch($job->id);

        return response()->json(['ok' => true, 'id' => $job->id]);
    }
}
