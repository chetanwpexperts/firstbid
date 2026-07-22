<?php

namespace App\Services;

use App\Models\UpworkJob;
use Illuminate\Support\Facades\Http;

class TelegramNotifier
{
    public function sendJobAlert(UpworkJob $job, ?string $chatId): void
    {
        if (! $chatId) {
            throw new \RuntimeException('User has no telegram_chat_id set');
        }

        $flags = [];
        if (! $job->payment_verified)                                 $flags[] = '🚩 Payment NOT verified';
        if ($job->client_score !== null && $job->client_score < 4.0)  $flags[] = "🚩 Client rating {$job->client_score}";
        if (($job->client_hires ?? 1) === 0)                          $flags[] = '⚠️ New client, 0 hires';
        $flagText = $flags ? implode("\n", $flags) . "\n" : '';

        $header = sprintf(
            "🟢 <b>Score %s</b> — %s\n💰 %s | %s | ⭐ %s (%s hires)\n%s\n<b>Suggested:</b> %s",
            $job->uphunt_score ?? '?',
            e($job->title),
            e($job->budget_display),
            e($job->client_country ?? '?'),
            $job->client_score ?? '?',
            $job->client_hires ?? '?',
            $flagText,
            e($job->bid_suggestion ?? '')
        );

        $this->send($chatId, $header, [
            'inline_keyboard' => [[
                ['text' => '🔗 Open job on Upwork', 'url' => $job->job_url],
            ]],
        ]);

        $this->send($chatId, "📋 <b>Cover letter</b> (tap to copy):\n\n<code>" . e($job->cover_letter) . '</code>');

        if (! empty($job->question_answers)) {
            $qa = collect($job->question_answers)->map(function ($a) use ($job) {
                $q = collect($job->screening_questions)->firstWhere('position', $a['position'] ?? -1);
                return '❓ ' . e($q['question'] ?? 'Question') . "\n<code>" . e($a['answer'] ?? '') . '</code>';
            })->implode("\n\n");
            $this->send($chatId, "📝 <b>Screening answers</b>:\n\n" . $qa);
        }
    }

    /**
     * Light notice for a job that was captured but not auto-generated —
     * no cover letter exists yet, so unlike sendJobAlert this is a single
     * message with a link back to the dashboard to generate one manually.
     */
    public function sendJobCaptured(UpworkJob $job, ?string $chatId): void
    {
        if (! $chatId) {
            return;
        }

        $flags = [];
        if (! $job->payment_verified)                                 $flags[] = '🚩 Payment NOT verified';
        if ($job->client_score !== null && $job->client_score < 4.0)  $flags[] = "🚩 Client rating {$job->client_score}";
        if (($job->client_hires ?? 1) === 0)                          $flags[] = '⚠️ New client, 0 hires';
        $flagText = $flags ? implode("\n", $flags) . "\n" : '';

        $url = rtrim(config('app.url'), '/') . '/jobs/' . $job->id;

        $text = sprintf(
            "🟡 <b>New job — Score %s</b>\n%s\n💰 %s | %s\n%sTap to review &amp; generate your letter.",
            $job->uphunt_score ?? '?',
            e($job->title),
            e($job->budget_display),
            e($job->client_country ?? '?'),
            $flagText
        );

        $this->send($chatId, $text, [
            'inline_keyboard' => [[
                ['text' => '📋 Review & Generate', 'url' => $url],
            ]],
        ]);
    }

    public function sendText(?string $chatId, string $text): void
    {
        if ($chatId) {
            $this->send($chatId, $text);
        }
    }

    private function send(string $chatId, string $text, ?array $replyMarkup = null): void
    {
        $payload = [
            'chat_id'    => $chatId,
            'text'       => mb_substr($text, 0, 4000),
            'parse_mode' => 'HTML',
        ];
        if ($replyMarkup) {
            $payload['reply_markup'] = json_encode($replyMarkup);
        }

        Http::timeout(15)
            ->post('https://api.telegram.org/bot' . config('services.telegram.bot_token') . '/sendMessage', $payload)
            ->throw();
    }
}
