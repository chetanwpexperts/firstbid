<?php

namespace App\Jobs;

use App\Models\UpworkJob;
use App\Models\User;
use App\Services\ProposalAI;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateExtensionProposalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 90;

    public function __construct(public int $jobId, public int $userId) {}

    public function handle(ProposalAI $ai): void
    {
        $job = UpworkJob::find($this->jobId);
        $user = User::find($this->userId);

        if (! $job || ! $user || $job->cover_letter) {
            return;
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
                'budget_reasoning'   => $result['budget_reasoning'] ?? null,
                'task_breakdown'     => $result['task_breakdown'] ?? null,
                'status'             => 'generated',
            ]);

            $user->increment('letters_used');
        } catch (\Throwable $e) {
            $job->update(['status' => 'failed', 'skip_reason' => 'AI: ' . mb_substr($e->getMessage(), 0, 200)]);
            Log::error("Extension proposal generation error for job {$job->id}: " . $e->getMessage());
        }
    }
}
