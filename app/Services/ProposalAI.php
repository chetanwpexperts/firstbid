<?php

namespace App\Services;

use App\Models\UpworkJob;
use Illuminate\Support\Facades\Http;

class ProposalAI
{
    /**
     * @return array{cover_letter:string, question_answers:array, bid_suggestion:string, estimated_budget:string, estimated_duration:string, budget_reasoning:string, task_breakdown:array}
     */
    public function generate(UpworkJob $job, string $profile): array
    {
        if (trim($profile) === '') {
            throw new \RuntimeException('User has no proposal profile set');
        }

        $questions = collect($job->screening_questions ?? [])
            ->map(fn ($q) => ($q['position'] ?? 0) . '. ' . ($q['question'] ?? ''))
            ->implode("\n");

        $prompt = <<<PROMPT
You are an expert technical estimator & proposal writer for an Upwork freelancer. Sound like a real human developer — short sentences, specific, no buzzwords ("seamless", "leverage", "cutting-edge" are banned). Never open with "Hi", "Dear client", "I hope", or "I am excited".

FREELANCER PROFILE:
{$profile}

JOB:
Title: {$job->title}
Type: {$job->job_type} | Budget: {$job->budget_display} | Tier: {$job->contractor_tier}
Client: {$job->client_country}, rating {$job->client_score}, {$job->client_hires} past hires, payment verified: {$job->payment_verified}

DESCRIPTION:
{$job->description}

SCREENING QUESTIONS (may be empty):
{$questions}

ESTIMATION & BUDGET CALCULATOR INSTRUCTIONS (DO NOT ESTIMATE BLINDLY — USE RIGOROUS MATHEMATICAL LOGIC):
1. Analyze the job description and break down the work into 2 to 5 specific technical subtasks with estimated hours.
2. Sum subtask hours to get TOTAL_HOURS.
3. Calculate ESTIMATED_DURATION realistically based on TOTAL_HOURS (e.g. 1-2 Days for <= 8h, 3-5 Days for 10-20h, 7-10 Days for 25-35h, 10-14+ Days for 40+h).
4. Calculate ESTIMATED_BUDGET logically:
   - HOURLY JOBS: Suggest an hourly rate in the client's stated range matching freelancer profile skills.
   - FIXED JOBS:
     * If client budget matches work complexity (e.g. $150 for 6 hours work), set estimated_budget to "$135 fixed".
     * If client budget is a low placeholder (e.g. $100 for a 35+ hour / 10+ day project), set estimated_budget to "$100 Milestone 1 / $600 Full Scope".
5. Provide budget_reasoning: a 2-3 sentence mathematical & strategic justification explaining the total hours, subtask breakdown, and why this budget & duration were selected.

Rules for Proposal:
- Cover letter 120-180 words. First 2 lines must address the client's exact problem (only ~2 lines show in preview).
- Reference max 1-2 genuinely relevant past projects from the profile. If nothing is relevant, don't force it.
- Include one concrete first step for THIS job.
- End with a short question or call to action.
- Answer each screening question in 2-4 honest sentences based on the profile.
- bid_suggestion: one short line, e.g. "Bid \$35/hr (their range 30-60, mid-low wins here)".

Respond ONLY with valid JSON, no markdown fences:
{
  "cover_letter": "...",
  "question_answers": [{"position":0, "answer":"..."}],
  "bid_suggestion": "...",
  "estimated_budget": "$120 fixed",
  "estimated_duration": "3-5 Days (16 Hours)",
  "budget_reasoning": "Work breaks down into 3 tasks totaling 16 hours. At competitive rates, a bid of $120 delivered in 4 days provides great value.",
  "task_breakdown": [{"task": "Backend API integration", "hours": 8}, {"task": "UI implementation", "hours": 8}]
}
PROMPT;

        $response = Http::timeout(90)
            ->withHeaders([
                'x-api-key'         => config('services.anthropic.key'),
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])
            ->post('https://api.anthropic.com/v1/messages', [
                'model'      => config('services.anthropic.model', 'claude-sonnet-4-6'),
                'max_tokens' => 1400,
                'messages'   => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ])
            ->throw()
            ->json();

        $text = collect($response['content'] ?? [])
            ->where('type', 'text')
            ->pluck('text')
            ->implode("\n");

        $text  = trim(preg_replace('/```json|```/', '', $text));
        $start = strpos($text, '{');
        $end   = strrpos($text, '}');
        $data  = json_decode(substr($text, $start, $end - $start + 1), true);

        if (! is_array($data) || empty($data['cover_letter'])) {
            throw new \RuntimeException('AI returned unparseable response');
        }

        return [
            'cover_letter'       => $data['cover_letter'],
            'question_answers'   => $data['question_answers'] ?? [],
            'bid_suggestion'     => $data['bid_suggestion'] ?? '',
            'estimated_budget'   => $data['estimated_budget'] ?? '',
            'estimated_duration' => $data['estimated_duration'] ?? '',
            'budget_reasoning'  => $data['budget_reasoning'] ?? '',
            'task_breakdown'     => $data['task_breakdown'] ?? [],
        ];
    }
}
