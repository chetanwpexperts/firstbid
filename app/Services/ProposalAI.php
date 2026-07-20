<?php

namespace App\Services;

use App\Models\UpworkJob;
use Illuminate\Support\Facades\Http;

class ProposalAI
{
    /**
     * @return array{cover_letter:string, question_answers:array, bid_suggestion:string}
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
You are writing an Upwork proposal for this freelancer. Sound like a real human developer — short sentences, specific, no buzzwords ("seamless", "leverage", "cutting-edge" are banned). Never open with "Hi", "Dear client", "I hope", or "I am excited".

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

Rules:
- Cover letter 120-180 words. First 2 lines must address the client's exact problem (only ~2 lines show in preview).
- Reference max 1-2 genuinely relevant past projects from the profile. If nothing is relevant, don't force it.
- Include one concrete first step for THIS job.
- End with a short question or call to action.
- Answer each screening question in 2-4 honest sentences based on the profile.
- bid_suggestion: one short line, e.g. "Bid \$35/hr (their range 30-60, mid-low wins here)".

Respond ONLY with valid JSON, no markdown fences:
{"cover_letter":"...","question_answers":[{"position":0,"answer":"..."}],"bid_suggestion":"..."}
PROMPT;

        $response = Http::timeout(90)
            ->withHeaders([
                'x-api-key'         => config('services.anthropic.key'),
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])
            ->post('https://api.anthropic.com/v1/messages', [
                'model'      => config('services.anthropic.model', 'claude-sonnet-4-6'),
                'max_tokens' => 1200,
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
            'cover_letter'     => $data['cover_letter'],
            'question_answers' => $data['question_answers'] ?? [],
            'bid_suggestion'   => $data['bid_suggestion'] ?? '',
        ];
    }
}
