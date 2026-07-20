@extends('layout')
@section('title', 'Settings — FirstBid')
@section('content')
<h1>Settings</h1>

<div class="panel">
  <h2>1 · Your webhook URL</h2>
  <p class="help" style="margin-bottom:8px">Paste this into UpHunt → Notification Settings → Webhook → <b>Set</b>. Every matched job will be sent here automatically.</p>
  <div class="codebox">
    <span id="wh">{{ rtrim(config('app.url'), '/') }}/api/hook/{{ $user->webhook_token }}</span>
    <button class="copybtn" onclick="copyText('wh', this)">Copy</button>
  </div>
  <p class="help">Keep it secret — anyone with this URL can send jobs to your account.</p>
</div>

<form method="POST" action="{{ route('settings.update') }}">
  @csrf
  <div class="panel">
    <h2>2 · Your profile (the AI writes letters from this)</h2>
    <label>Describe yourself: skills, years, 3–5 real past projects, working style</label>
    <textarea name="proposal_profile" required minlength="100" placeholder="Example: Freelance PHP developer, 8+ years. Laravel, CodeIgniter, WordPress, MySQL, payment integrations. Recent work: custom CRM for a UK client (tickets, digital signatures, roles)...">{{ old('proposal_profile', $user->proposal_profile) }}</textarea>
    <p class="help">Be specific — real project names and outcomes make letters that win. Generic profiles make generic letters.</p>

    <label>Minimum match score (skip jobs below this, 1–10)</label>
    <input type="number" name="min_score" min="1" max="10" value="{{ old('min_score', $user->min_score) }}" style="width:90px">
    <div style="margin-top:16px"><button class="btn">Save settings</button></div>
  </div>
</form>

<div class="panel">
  <h2>3 · Telegram alerts</h2>
  @if ($user->telegram_chat_id)
    <p style="font-size:14.5px;margin-bottom:10px">✅ <b>Connected</b> — job alerts go to your Telegram.</p>
    <form method="POST" action="{{ route('settings.testTelegram') }}" style="display:inline">
      @csrf
      <button class="btn secondary">Send test message</button>
    </form>
    <a class="btn secondary" style="margin-left:6px" href="https://t.me/{{ config('services.telegram.bot_username') }}?start={{ $user->webhook_token }}" target="_blank" rel="noopener">Reconnect</a>
  @else
    <p class="help" style="margin-bottom:12px">One tap: opens Telegram, you press <b>Start</b>, and you're connected. (Install Telegram from the App Store / Play Store first if you don't have it.)</p>
    <a class="btn" href="https://t.me/{{ config('services.telegram.bot_username') }}?start={{ $user->webhook_token }}" target="_blank" rel="noopener">📲 Connect Telegram</a>
    <p class="help" style="margin-top:10px">After tapping Start in Telegram, refresh this page — it will show Connected.</p>
  @endif
</div>

<div class="panel">
  <h2>Plan</h2>
  @if ($user->plan === 'pro')
    <p style="font-size:14px"><b>Pro</b> · {{ $user->letters_used }}/{{ $user->letters_quota }} letters used this month</p>
  @elseif ($user->onTrial())
    <p style="font-size:14px">
      <b>Free trial</b> — {{ (int) now()->diffInDays($user->trial_ends_at) }} days left (ends {{ $user->trial_ends_at->format('d M Y') }})
      · {{ $user->letters_used }}/{{ $user->letters_quota }} letters used this month
    </p>
  @else
    <p style="font-size:14px;color:var(--red)"><b>Trial ended.</b> Paid plans coming soon — contact us to continue receiving letters.</p>
  @endif
</div>
@endsection
