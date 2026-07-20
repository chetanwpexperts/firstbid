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

        // ---- Trial check (plan 'pro' bypasses; everyone else needs an active trial) ----
        if ($user->plan !== 'pro' && ($user->trial_ends_at === null || now()->greaterThan($user->trial_ends_at))) {
            $job->update(['status' => 'skipped', 'skip_reason' => 'trial expired']);
            try {
                $telegram->sendText($user->telegram_chat_id,
                    "⏰ Your FirstBid 30-day trial has ended. Paid plans are coming soon — reply to this or contact us to keep your letters flowing.");
            } catch (\Throwable) {}
            return;
        }

        // ---- Monthly quota reset ----
        if ($user->quota_reset_at === null || now()->greaterThan($user->quota_reset_at)) {
            $user->forceFill(['letters_used' => 0, 'quota_reset_at' => now()->addMonth()])->save();
        }

        // ---- Filters ----
        if ($job->uphunt_score !== null && $job->uphunt_score < $user->min_score) {
            $job->update(['status' => 'skipped', 'skip_reason' => "score {$job->uphunt_score} < {$user->min_score}"]);
            return;
        }

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
            $result = $ai->generate($job, $user->proposal_profile ?? '');

            $job->update([
                'cover_letter'     => $result['cover_letter'],
                'question_answers' => $result['question_answers'],
                'bid_suggestion'   => $result['bid_suggestion'],
                'status'           => 'generated',
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
