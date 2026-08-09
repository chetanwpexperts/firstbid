<?php

namespace App\Http\Controllers;

use App\Models\UpworkJob;
use App\Models\User;
use App\Services\ProposalAI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExtensionApiController extends Controller
{
    /**
     * Check extension user account and trial status
     * GET /api/extension/status
     */
    public function status(Request $request)
    {
        $token = $request->header('X-Webhook-Token') ?? $request->input('token');
        $user = User::where('webhook_token', $token)->first();

        if (! $user) {
            return response()->json(['connected' => false, 'error' => 'Invalid Webhook Token'], 401);
        }

        return response()->json([
            'connected'       => true,
            'is_approved'     => (bool) $user->is_approved,
            'can_generate'    => $user->canGenerate(),
            'letters_used'    => $user->letters_used,
            'monthly_quota'   => $user->monthly_quota,
            'quota_remaining' => max(0, $user->monthly_quota - $user->letters_used),
        ]);
    }

    /**
     * Endpoint for Chrome / Cross-Browser Extension: POST /api/extension/generate
     * Header: X-Webhook-Token
     * Body: { title, description, jobUrl, ciphertext, budget, jobType, screeningQuestions }
     */
    public function generate(Request $request, ProposalAI $ai)
    {
        $token = $request->header('X-Webhook-Token') ?? $request->input('token');
        $user = User::where('webhook_token', $token)->first();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized: Invalid FirstBid.in Webhook Token.'], 401);
        }

        if (! $user->is_approved) {
            return response()->json(['error' => 'Your account is pending admin approval.'], 403);
        }

        if (! $user->canGenerate()) {
            return response()->json(['error' => 'Your free trial has ended or monthly proposal limit reached. Please upgrade your plan on FirstBid.in to continue.'], 403);
        }

        $p = $request->all();
        $ciphertext = $p['ciphertext'] ?? null;
        $jobUrl = $p['jobUrl'] ?? $p['job_url'] ?? null;

        if (! $ciphertext && $jobUrl) {
            if (preg_match('#upwork\.com/jobs/([~_%a-zA-Z0-9]+)#', urldecode($jobUrl), $m)) {
                $ciphertext = $m[1];
            }
        }

        $job = UpworkJob::where('user_id', $user->id)
            ->where(function ($q) use ($ciphertext, $jobUrl) {
                if ($ciphertext) $q->where('ciphertext', $ciphertext);
                if ($jobUrl) $q->orWhere('job_url', $jobUrl);
            })
            ->first();

        if (! $job) {
            $job = UpworkJob::create([
                'user_id'             => $user->id,
                'source'              => 'extension',
                'ciphertext'          => $ciphertext,
                'title'               => $p['title'] ?? 'Upwork Job',
                'description'         => $p['description'] ?? '',
                'job_url'             => $jobUrl,
                'job_type'            => $p['jobType'] ?? null,
                'budget_display'      => $p['budget'] ?? 'See job post',
                'payment_verified'    => true,
                'screening_questions' => $p['screeningQuestions'] ?? [],
                'raw_payload'         => $p,
                'status'              => 'received',
            ]);
        }

        // If cover letter already exists, return existing proposal fast
        if ($job->cover_letter) {
            return response()->json([
                'success'            => true,
                'job_id'             => $job->id,
                'cover_letter'       => $job->cover_letter,
                'opener_hooks'       => $job->opener_hooks,
                'milestones'         => $job->milestones,
                'question_answers'   => $job->question_answers,
                'estimated_budget'   => $job->estimated_budget,
                'estimated_duration' => $job->estimated_duration,
                'task_breakdown'     => $job->task_breakdown,
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

            return response()->json([
                'success'            => true,
                'job_id'             => $job->id,
                'cover_letter'       => $result['cover_letter'],
                'opener_hooks'       => $result['opener_hooks'] ?? null,
                'milestones'         => $result['milestones'] ?? null,
                'question_answers'   => $result['question_answers'] ?? [],
                'estimated_budget'   => $result['estimated_budget'] ?? null,
                'estimated_duration' => $result['estimated_duration'] ?? null,
                'task_breakdown'     => $result['task_breakdown'] ?? [],
            ]);
        } catch (\Throwable $e) {
            Log::error("Extension proposal generation error: " . $e->getMessage());
            return response()->json(['error' => 'AI Generation Error: ' . $e->getMessage()], 500);
        }
    }
}
