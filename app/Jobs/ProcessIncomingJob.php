<?php

namespace App\Jobs;

use App\Models\UpworkJob;
use App\Services\ProposalAI;
use App\Services\TelegramNotifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessIncomingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 120;

    public function __construct(public int $jobId) {}

    public function handle(ProposalAI $ai, TelegramNotifier $telegram): void
    {
        $job = UpworkJob::with('user')->find($this->jobId);
        if (! $job || $job->status !== 'received' || ! $job->user) {
            return;
        }

        $user = $job->user;

        // ---- Account approval check ----
        if (! $user->is_approved) {
            $job->update(['status' => 'skipped', 'skip_reason' => 'account pending approval']);
            return;
        }

        // ---- Trial check (UpHunt real-time webhook requires 'pro' or active trial; email-source jobs always load) ----
        if ($job->source !== 'email' && $user->plan !== 'pro' && ! $user->onTrial()) {
            $job->update(['status' => 'skipped', 'skip_reason' => 'UpHunt trial expired — upgrade to Pro for real-time webhooks']);
            try {
                $telegram->sendText($user->telegram_chat_id,
                    "⏰ Your UpHunt trial has ended. Webhook alerts require Pro — email alerts continue working!");
            } catch (\Throwable) {}
            return;
        }

        // ---- Monthly quota reset ----
        if ($user->quota_reset_at === null || now()->greaterThan($user->quota_reset_at)) {
            $user->forceFill(['letters_used' => 0, 'quota_reset_at' => now()->addMonth()])->save();
        }

        // ---- Filters (checked before any generation happens) ----
        if ($user->skip_unverified_payment && ! $job->payment_verified) {
            $job->update(['status' => 'skipped', 'skip_reason' => 'payment not verified']);
            return;
        }

        // Email-source jobs have uphunt_score = null (no score available) —
        // null must pass this filter rather than being skipped.
        if ($job->uphunt_score !== null) {
            $passes = $user->min_score_operator === '>'
                ? $job->uphunt_score > $user->min_score
                : $job->uphunt_score >= $user->min_score;
            if (! $passes) {
                $job->update(['status' => 'skipped', 'skip_reason' => "score {$job->uphunt_score} does not meet your {$user->min_score_operator}{$user->min_score} filter"]);
                return;
            }
        }

        // ---- Default: capture the job, let the user click Generate ----
        if (! $user->auto_generate) {
            $job->update(['status' => 'ready_to_generate']);
            try {
                $telegram->sendJobCaptured($job->fresh('user'), $user->telegram_chat_id);
            } catch (\Throwable $e) {
                Log::warning("Telegram capture notice failed for job {$job->id}: " . $e->getMessage());
            }
            return;
        }

        // ---- Opted into auto-generate: same quota-gated flow as before ----
        if ($user->letters_used >= $user->letters_quota) {
            $job->update(['status' => 'skipped', 'skip_reason' => 'monthly quota reached']);
            try {
                $telegram->sendText($user->telegram_chat_id,
                    "⚠️ Monthly letter limit reached ({$user->letters_quota}). Resets on {$user->quota_reset_at?->format('d M')}.");
            } catch (\Throwable) {}
            return;
        }

        // ---- Generate ----
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
                'status'             => 'applied',
                'applied_at'         => now(),
            ]);

            $user->increment('letters_used');
        } catch (\Throwable $e) {
            $job->update(['status' => 'failed', 'skip_reason' => 'AI: ' . mb_substr($e->getMessage(), 0, 200)]);
            Log::error("AI generation failed for job {$job->id}: " . $e->getMessage());
            return;
        }

        // ---- Notify ----
        try {
            $telegram->sendJobAlert($job->fresh('user'), $user->telegram_chat_id);
            $job->update(['status' => 'notified']);
        } catch (\Throwable $e) {
            $job->update(['status' => 'failed', 'skip_reason' => 'Telegram: ' . mb_substr($e->getMessage(), 0, 200)]);
            Log::error("Telegram failed for job {$job->id}: " . $e->getMessage());
        }
    }
}
