<?php

namespace App\Http\Controllers;

use App\Models\InboundEmail;
use App\Services\TelegramNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SettingsController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();

        // Works with either structure: settings/edit.blade.php or settings.blade.php (root)
        $view = View::exists('settings.edit') ? 'settings.edit' : 'settings';

        return view($view, [
            'user'              => $user,
            'lastEmail'         => InboundEmail::where('user_id', $user->id)->latest()->first(),
            'verificationEmail' => InboundEmail::where('user_id', $user->id)->where('status', 'verification')->latest()->first(),
        ]);
    }

    public function verification(Request $request)
    {
        $email = InboundEmail::where('user_id', $request->user()->id)
            ->where('status', 'verification')
            ->latest()
            ->firstOrFail();

        return view('settings.verification', ['email' => $email]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'proposal_profile' => ['required', 'string', 'min:100', 'max:4000'],
            'telegram_chat_id' => ['nullable', 'string', 'max:32'],
            'min_score'        => ['required', 'integer', 'between:1,10'],
        ]);

        $request->user()->update($data);

        return back()
            ->with('status', 'Settings saved.')
            ->with('ok', 'Settings saved.');
    }

    public function testTelegram(Request $request, TelegramNotifier $telegram)
    {
        $chatId = $request->user()->telegram_chat_id;

        if (! $chatId) {
            return back()->withErrors(['telegram_chat_id' => 'Save your chat ID first, then test.']);
        }

        try {
            $telegram->sendText($chatId, "✅ FirstBid connected! Job alerts will arrive here.");

            return back()
                ->with('status', 'Test message sent — check your Telegram.')
                ->with('ok', 'Test message sent — check your Telegram.');
        } catch (\Throwable $e) {
            return back()->withErrors(['telegram_chat_id' => 'Sending failed: ' . $e->getMessage()]);
        }
    }
}