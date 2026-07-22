<?php

namespace App\Services;

use App\Models\UpworkJob;
use Illuminate\Support\Facades\Http;

class ProposalAI
{
    /**
     * @return array{cover_letter:string, opener_hooks:array, milestones:array, matched_portfolio:array, question_answers:array, bid_suggestion:string, estimated_budget:string, estimated_duration:string, budget_reasoning:string, task_breakdown:array}
     */
    public function generate(UpworkJob $job, string $profile, array $portfolioProjects = []): array
    {
        if (trim($profile) === '') {
            throw new \RuntimeException('User has no proposal profile set');
        }

        $questions = collect($job->screening_questions ?? [])
            ->map(fn ($q) => ($q['position'] ?? 0) . '. ' . ($q['question'] ?? ''))
            ->implode("\n");

        $portfolioJson = !empty($portfolioProjects) ? json_encode($portfolioProjects) : 'None provided';

        $prompt = <<<PROMPT
You are an expert technical estimator & proposal writer for an Upwork freelancer. Sound like a real human developer — short sentences, specific, no buzzwords ("seamless", "leverage", "cutting-edge" are banned). Never open with "Hi", "Dear client", "I hope", or "I am excited".

FREELANCER PROFILE:
{$profile}

FREELANCER PORTFOLIO PROJECTS:
{$portfolioJson}

JOB:
Title: {$job->title}
Type: {$job->job_type} | Budget: {$job->budget_display} | Tier: {$job->contractor_tier}
Client: {$job->client_country}, rating {$job->client_score}, {$job->client_hires} past hires, payment verified: {$job->payment_verified}

DESCRIPTION:
{$job->description}

SCREENING QUESTIONS (may be empty):
{$questions}

ESTIMATION & BUDGET CALCULATOR INSTRUCTIONS:
1. Break down the work into 2 to 5 specific technical subtasks with estimated hours.
2. Sum subtask hours to get total effort hours.
3. Calculate estimated_duration realistically (e.g. 1-2 Days for <= 8h, 3-5 Days for 10-20h, 7-10 Days for 25-35h, 10-14+ Days for 40+h).
4. Calculate estimated_budget logically.
5. Provide budget_reasoning: 2-3 sentence mathematical & strategic justification.
6. Generate 3 distinct Opening Hooks ("First 2 Lines" Options) for the proposal:
   - "problem_direct": Direct technical diagnosis of the client's core problem.
   - "results_first": Metrics & past result-driven opening line.
   - "fast_delivery": Execution speed and staging validation opener.
7. Generate 3 formal Upwork Deposit Milestones (Phase 1, Phase 2, Phase 3 with deliverables & amount).
8. If portfolio projects were provided, pick the single best matching portfolio item into `matched_portfolio` (title & snippet).

Respond ONLY with valid JSON, no markdown fences:
{
  "cover_letter": "...",
  "opener_hooks": {
    "problem_direct": "Your Laravel app's speed issues are affecting real users — I will profile slow queries and indexes first...",
    "results_first": "Cut average page load time from 4.2s to 1.1s on a similar Laravel platform handling 50k+ requests...",
    "fast_delivery": "I can set up Telescope profiling today and deploy query fixes to staging within 48 hours..."
  },
  "milestones": [
    {"phase": "Milestone 1", "title": "Audit & Profiling", "amount": "$150", "days": "2 Days", "deliverables": "Database query audit & Telescope baseline metrics"},
    {"phase": "Milestone 2", "title": "Query & Caching Fixes", "amount": "$350", "days": "3 Days", "deliverables": "N+1 query fixes, index creation, Redis route caching"},
    {"phase": "Milestone 3", "title": "Staging Testing & Go-Live", "amount": "$150", "days": "2 Days", "deliverables": "Staging regression benchmarking & production release"}
  ],
  "matched_portfolio": {
    "title": "High-Traffic CRM Optimization",
    "snippet": "Optimized Eloquent relationships & Redis caching for 50k monthly active users, dropping load time by 65%."
  },
  "question_answers": [{"position":0, "answer":"..."}],
  "bid_suggestion": "...",
  "estimated_budget": "$650 fixed",
  "estimated_duration": "5-7 Days (18 Hours)",
  "budget_reasoning": "Work breaks down into 4 subtasks totaling 18 hours. At $35-$40/hr, $650 delivered across 3 milestones provides optimal security.",
  "task_breakdown": [{"task": "Database audit & profiling", "hours": 3}, {"task": "Eloquent & N+1 fixes", "hours": 6}, {"task": "Redis caching layer", "hours": 4}, {"task": "Staging & Go-live", "hours": 5}]
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
                'max_tokens' => 1600,
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
            'cover_letter'      => $data['cover_letter'],
            'opener_hooks'      => $data['opener_hooks'] ?? [],
            'milestones'        => $data['milestones'] ?? [],
            'matched_portfolio' => $data['matched_portfolio'] ?? [],
            'question_answers'  => $data['question_answers'] ?? [],
            'bid_suggestion'    => $data['bid_suggestion'] ?? '',
            'estimated_budget'  => $data['estimated_budget'] ?? '',
            'estimated_duration'=> $data['estimated_duration'] ?? '',
            'budget_reasoning' => $data['budget_reasoning'] ?? '',
            'task_breakdown'    => $data['task_breakdown'] ?? [],
        ];
    }
}
