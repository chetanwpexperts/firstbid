@extends('layout')

@section('title', 'Settings — FirstBid AI')

@section('content')
<h1 style="font-size: 24px; font-weight: 800; color: #fff; margin-bottom: 20px;">Account & AI Settings</h1>

<div class="glass-panel">
  <h2 style="font-size: 12px; font-family: var(--font-mono); text-transform: uppercase; color: var(--emerald-light); margin-bottom: 8px;">1 · Your Personal Webhook URL</h2>
  <p style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 12px;">Paste this URL into UpHunt → Notification Settings → Webhook. Job alerts will stream into your inbox automatically.</p>
  
  <div style="display: flex; gap: 10px; align-items: center;">
    <input type="text" id="whurl" readonly value="{{ url('/api/hook/' . $user->webhook_token) }}" style="font-family: var(--font-mono); font-size: 13px;">
    <button class="btn btn-ghost btn-sm" onclick="copyVal('whurl', this)" style="flex: none;">Copy URL</button>
  </div>
  <p style="font-size: 12px; color: var(--text-dim); margin-top: 6px;">Keep this URL private — anyone with this URL can send job webhooks to your account.</p>
</div>

<form method="POST" action="{{ route('settings.update') }}">
  @csrf
  <div class="glass-panel">
    <h2 style="font-size: 12px; font-family: var(--font-mono); text-transform: uppercase; color: var(--emerald-light); margin-bottom: 8px;">2 · Freelancer AI Profile</h2>
    <label class="form-label">Describe your core skills, years of experience, past projects, and working style:</label>
    <textarea name="proposal_profile" rows="9" required placeholder="Example: Freelance Full-Stack PHP & Laravel developer, 8+ years. Specialized in high-speed web apps, MySQL optimization, and payment APIs. Recent work: Custom CRM for a UK client with real-time tickets and digital signatures...">{{ old('proposal_profile', $user->proposal_profile) }}</textarea>
    <p style="font-size: 12.5px; color: var(--text-muted); margin-top: 6px;">Be specific — real project details produce winning, tailored proposals.</p>

    <div style="margin-top: 20px; border-top: 1px solid var(--border); padding-top: 16px;">
      <label class="form-label">Minimum Match Score Filter (1–10):</label>
      <div style="display: flex; gap: 10px; align-items: center;">
        <select name="min_score_operator" style="max-width: 90px;">
          <option value=">=" {{ old('min_score_operator', $user->min_score_operator) === '>=' ? 'selected' : '' }}>&gt;=</option>
          <option value=">" {{ old('min_score_operator', $user->min_score_operator) === '>' ? 'selected' : '' }}>&gt;</option>
        </select>
        <input type="number" name="min_score" min="1" max="10" value="{{ old('min_score', $user->min_score) }}" style="max-width: 100px;">
      </div>
    </div>

    <div style="margin-top: 20px; border-top: 1px solid var(--border); padding-top: 16px;">
      <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; color: var(--text-main); font-size: 14px;">
        <input type="checkbox" name="skip_unverified_payment" value="1" {{ old('skip_unverified_payment', $user->skip_unverified_payment) ? 'checked' : '' }} style="width: auto;">
        <span>Only process <b>Payment Verified</b> jobs (skip unverified client listings to protect quota)</span>
      </label>

      <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; color: var(--text-main); font-size: 14px; margin-top: 12px;">
        <input type="checkbox" name="auto_generate" value="1" {{ old('auto_generate', $user->auto_generate) ? 'checked' : '' }} style="width: auto;">
        <span>Automatically generate AI proposal when alert arrives (uncheck to generate manually on-demand)</span>
      </label>
    </div>
  </div>

  <div class="glass-panel">
    <h2 style="font-size: 12px; font-family: var(--font-mono); text-transform: uppercase; color: var(--emerald-light); margin-bottom: 8px;">3 · Telegram Alert Delivery</h2>
    <label class="form-label">Your Telegram Chat ID:</label>
    <input type="text" name="telegram_chat_id" value="{{ old('telegram_chat_id', $user->telegram_chat_id) }}" placeholder="e.g. 6234567890" style="max-width: 320px;">
    
    <div style="margin-top: 16px;">
      <button class="btn" type="submit">Save All Settings</button>
    </div>
  </div>
</form>

<form method="POST" action="{{ route('settings.testTelegram') }}" class="glass-panel">
  @csrf
  <h2 style="font-size: 12px; font-family: var(--font-mono); text-transform: uppercase; color: var(--emerald-light); margin-bottom: 8px;">4 · Test Telegram Connection</h2>
  <p style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 14px;">Send a test message to your saved Telegram chat ID to verify delivery.</p>
  <button class="btn btn-ghost" type="submit">Send Test Message 📲</button>
</form>

<div class="glass-panel">
  <h2 style="font-size: 12px; font-family: var(--font-mono); text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Plan & Usage</h2>
  <p style="font-size: 15px; color: #fff;">
    Current Plan: <b style="text-transform: uppercase; color: var(--emerald-light);">{{ $user->plan }}</b> —
    <span style="font-family: var(--font-mono);">{{ $user->letters_used }} / {{ $user->letters_quota }}</span> proposals used this month.
  </p>
</div>
@endsection
