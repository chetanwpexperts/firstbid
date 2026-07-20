@extends('layout')
@section('title', 'Settings — FirstBid')
@section('content')
<h1>Settings</h1>

<div class="panel">
  <h2>1 · Your webhook URL</h2>
  <p class="help">Paste this into UpHunt → Notification Settings → Webhook → <b>Set</b>. Every matched job will be sent here automatically.</p>
  <div class="copyrow">
    <input type="text" id="whurl" readonly value="{{ url('/api/hook/' . $user->webhook_token) }}">
    <button class="btn ghost sm" onclick="copyVal('whurl', this)">Copy</button>
  </div>
  <p class="help">Keep it secret — anyone with this URL can send jobs to your account.</p>
</div>

<form method="POST" action="{{ route('settings.update') }}">
  @csrf
  <div class="panel">
    <h2>2 · Your profile (the AI writes letters from this)</h2>
    <label>Describe yourself: skills, years, 3–5 real past projects, working style</label>
    <textarea name="proposal_profile" rows="10" required placeholder="Example: Freelance PHP developer, 8+ years. Laravel, CodeIgniter, WordPress, MySQL, payment integrations. Recent work: custom CRM for a UK client (tickets, digital signatures, roles)...">{{ old('proposal_profile', $user->proposal_profile) }}</textarea>
    <p class="help">Be specific — real project names and outcomes make letters that win. Generic profiles make generic letters.</p>

    <label>Minimum match score (skip jobs below this, 1–10)</label>
    <input type="number" name="min_score" min="1" max="10" value="{{ old('min_score', $user->min_score) }}" style="max-width:110px">
  </div>

  <div class="panel">
    <h2>3 · Telegram alerts</h2>
    <label>Your Telegram chat ID</label>
    <input type="text" name="telegram_chat_id" value="{{ old('telegram_chat_id', $user->telegram_chat_id) }}" placeholder="e.g. 6234567890" style="max-width:260px">
    <p class="help">
      How to get it: open Telegram → search <code class="k">{{ config('services.telegram.bot_name', '@your_firstbid_bot') }}</code>
      → press <b>Start</b> → send any message → then open
      <code class="k">https://api.telegram.org/bot&lt;token&gt;/getUpdates</code> and copy <code class="k">"chat":{"id": ... }</code>.
      (In the next version this becomes one click.)
    </p>
    <div style="margin-top:10px;display:flex;gap:8px">
      <button class="btn" type="submit">Save settings</button>
    </div>
  </div>
</form>

<form method="POST" action="{{ route('settings.testTelegram') }}" class="panel">
  @csrf
  <h2>4 · Test</h2>
  <p class="help" style="margin-bottom:10px">Sends a test message to your saved chat ID.</p>
  <button class="btn ghost" type="submit">Send test message</button>
</form>

<div class="panel">
  <h2>Plan</h2>
  <p style="font-size:14px">
    <b style="text-transform:capitalize">{{ $user->plan }}</b> —
    {{ $user->letters_used }} / {{ $user->letters_quota }} letters used this month.
  </p>
</div>
@endsection
