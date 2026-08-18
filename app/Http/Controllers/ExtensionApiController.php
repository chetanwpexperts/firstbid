<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateExtensionProposalJob;
use App\Models\UpworkJob;
use App\Models\User;
use Illuminate\Http\Request;

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
    public function generate(Request $request)
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
                'status'             => 'ready',
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

        // Dispatch generation to the queue instead of blocking this request on the
        // AI call — avoids holding a PHP-FPM worker open for up to 60s per click.
        if ($job->status !== 'processing') {
            $job->update(['status' => 'processing']);
            GenerateExtensionProposalJob::dispatch($job->id, $user->id);
        }

        return response()->json([
            'success' => true,
            'status'  => 'processing',
            'job_id'  => $job->id,
        ]);
    }

    /**
     * Poll for the result of a queued generation.
     * GET /api/extension/generate/{id}/status
     */
    public function generateStatus(Request $request, int $id)
    {
        $token = $request->header('X-Webhook-Token') ?? $request->input('token');
        $user = User::where('webhook_token', $token)->first();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized: Invalid FirstBid.in Webhook Token.'], 401);
        }

        $job = UpworkJob::where('user_id', $user->id)->find($id);
        if (! $job) {
            return response()->json(['error' => 'Job not found.'], 404);
        }

        if ($job->cover_letter) {
            return response()->json([
                'success'            => true,
                'status'             => 'ready',
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

        if ($job->status === 'failed') {
            return response()->json([
                'success' => false,
                'status'  => 'failed',
                'error'   => $job->skip_reason ?? 'AI generation failed.',
            ]);
        }

        return response()->json(['success' => true, 'status' => 'processing']);
    }
}
