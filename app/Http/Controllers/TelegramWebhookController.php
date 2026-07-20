<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    /**
     * Telegram calls this for every message sent to the bot.
     * We only care about "/start <webhook_token>" — the one-click connect.
     * Secure the URL with a secret path segment set in .env.
     */
    public function handle(Request $request, string $secret)
    {
        if ($secret !== config('services.telegram.webhook_secret')) {
            return response()->json(['ok' => true]); // silently ignore
        }

        $message = $request->input('message');
        if (! $message || empty($message['text'])) {
            return response()->json(['ok' => true]);
        }

        $chatId = data_get($message, 'chat.id');
        $text   = trim($message['text']);

        // Expect: "/start <token>"
        if (str_starts_with($text, '/start')) {
            $token = trim(substr($text, 6));

            if ($token !== '') {
                $user = User::where('webhook_token', $token)->first();

                if ($user) {
                    $user->update(['telegram_chat_id' => (string) $chatId]);
                    $this->reply($chatId, "✅ Connected! Hi {$user->name} — your FirstBid job alerts will arrive right here. You can close this chat; we'll ping you when a matching job lands.");
                    Log::info("Telegram connected for user {$user->id}");
                } else {
                    $this->reply($chatId, "❌ This connect link looks invalid or expired. Open FirstBid → Settings and click Connect Telegram again.");
                }
            } else {
                $this->reply($chatId, "👋 To connect your FirstBid account, open FirstBid → Settings → Connect Telegram, and tap the button there.");
            }
        }

        return response()->json(['ok' => true]);
    }

    private function reply(string|int $chatId, string $text): void
    {
        try {
            Http::timeout(10)->post(
                'https://api.telegram.org/bot' . config('services.telegram.bot_token') . '/sendMessage',
                ['chat_id' => $chatId, 'text' => $text]
            );
        } catch (\Throwable $e) {
            Log::warning('Telegram reply failed: ' . $e->getMessage());
        }
    }
}
