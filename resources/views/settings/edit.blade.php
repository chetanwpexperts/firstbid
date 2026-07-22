@extends('layout')

@section('title', 'Settings — FirstBid')

@section('content')
<h1 style="font-size: 26px; font-weight: 800; color: var(--text-dark); margin-bottom: 24px;">Account & AI Settings</h1>

<form method="POST" action="{{ route('settings.update') }}">
  @csrf
  <div class="glass-panel" style="margin-bottom: 24px;">
    <h2 style="font-size: 13px; font-family: var(--font-mono); text-transform: uppercase; color: var(--upwork-dark); font-weight: 700; margin-bottom: 14px;">1 · Freelance Professional Profile</h2>
    <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 18px;">Providing accurate professional details allows FirstBid AI to craft hyper-tailored proposals and subtask effort breakdowns.</p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 16px;">
      <div class="form-group">
        <label class="form-label">Primary Upwork Niche / Specialty</label>
        <input type="text" name="niche" value="{{ old('niche', $user->niche) }}" placeholder="e.g. Full-Stack Laravel Developer, Mobile Dev">
      </div>

      <div class="form-group">
        <label class="form-label">Target Hourly Rate ($/hr)</label>
        <input type="number" step="0.01" name="hourly_rate" value="{{ old('hourly_rate', $user->hourly_rate) }}" placeholder="e.g. 45.00">
      </div>

      <div class="form-group">
        <label class="form-label">Years of Experience</label>
        <input type="text" name="years_experience" value="{{ old('years_experience', $user->years_experience) }}" placeholder="e.g. 8+ years">
      </div>

      <div class="form-group">
        <label class="form-label">Phone / WhatsApp Number</label>
        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="e.g. +1 (555) 000-0000">
      </div>

      <div class="form-group">
        <label class="form-label">Upwork Profile URL</label>
        <input type="url" name="upwork_url" value="{{ old('upwork_url', $user->upwork_url) }}" placeholder="https://www.upwork.com/freelancers/~01...">
      </div>

      <div class="form-group">
        <label class="form-label">Agency / Company Name (Optional)</label>
        <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}" placeholder="e.g. Apex Web Studio">
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">Detailed Skills, Past Projects & Writing Voice Profile:</label>
      <textarea name="proposal_profile" rows="6" required minlength="100" placeholder="Example: Freelance Full-Stack PHP & Laravel developer, 8+ years. Specialized in high-speed web apps, MySQL optimization, and payment APIs. Recent work: Custom CRM for a UK client with real-time tickets and digital signatures...">{{ old('proposal_profile', $user->proposal_profile) }}</textarea>
      <p style="font-size: 13px; color: var(--text-muted); margin-top: 6px;">Be specific — real project details produce winning proposals.</p>
    </div>

    <div style="margin-top: 22px; border-top: 1px solid var(--border); padding-top: 18px;">
      <label class="form-label">Minimum Match Score Filter:</label>
      <div style="display: flex; gap: 10px; align-items: center;">
        <span style="font-size: 13.5px; color: var(--text-muted);">Only jobs scored</span>
        <select name="min_score_operator" style="max-width: 95px;">
          <option value=">" {{ old('min_score_operator', $user->min_score_operator) === '>' ? 'selected' : '' }}>&gt;</option>
          <option value=">=" {{ old('min_score_operator', $user->min_score_operator) === '>=' ? 'selected' : '' }}>&ge;</option>
        </select>
        <input type="number" name="min_score" min="1" max="10" value="{{ old('min_score', $user->min_score) }}" style="max-width: 95px;">
      </div>
    </div>

    <div style="margin-top: 22px; border-top: 1px solid var(--border); padding-top: 18px;">
      <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; color: var(--text-dark); font-size: 14.5px;">
        <input type="checkbox" name="auto_generate" value="1" style="width: auto;" {{ old('auto_generate', $user->auto_generate) ? 'checked' : '' }}>
        <span>Automatically generate proposals for matching jobs (uncheck to generate manually on-demand)</span>
      </label>

      <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; color: var(--text-dark); font-size: 14.5px; margin-top: 14px;">
        <input type="checkbox" name="skip_unverified_payment" value="1" style="width: auto;" {{ old('skip_unverified_payment', $user->skip_unverified_payment) ? 'checked' : '' }}>
        <span>Skip jobs where client payment is not verified</span>
      </label>
    </div>

    <div style="margin-top: 22px;">
      <button class="btn" type="submit">Save All Settings</button>
    </div>
  </div>
</form>

<div class="glass-panel">
  <h2 style="font-size: 13px; font-family: var(--font-mono); text-transform: uppercase; color: var(--upwork-dark); font-weight: 700; margin-bottom: 8px;">2 · Get jobs by email (Free Forwarding)</h2>
  <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 12px;">Forward your Upwork job-alert emails to this address and we'll turn them into AI proposals automatically.</p>
  <div style="display: flex; gap: 10px; align-items: center;">
    <input type="text" id="inbox" readonly value="u_{{ $user->webhook_token }}@mail.firstbidin.com" style="font-family: var(--font-mono); font-size: 13.5px; background: var(--bg-subtle);">
    <button class="btn btn-ghost btn-sm" onclick="copyVal('inbox', this)" style="flex: none;">Copy Address</button>
  </div>

  @if ($verificationEmail)
    <div class="flash-toast err" style="margin-top: 14px; background: var(--amber-tint); border-color: var(--amber-border); color: var(--amber);">
      Gmail sent a verification email — <a href="{{ route('settings.verification') }}" style="color: inherit; text-decoration: underline;">open it below to activate forwarding</a>.
    </div>
  @endif

  <div style="margin-top: 16px; font-size: 13.5px; color: var(--text-muted); line-height: 1.8;">
    <ol style="margin-left: 18px;">
      <li>On Upwork, create <b>Saved Searches</b> for your skills and enable email alerts.</li>
      <li>In Gmail, create a filter: <code style="font-family: var(--font-mono); background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">from:donotreply@upwork.com</code> → Forward to the address above.</li>
      <li>When Gmail sends confirmation, click the verification link on this page to activate.</li>
    </ol>
  </div>

  <p style="font-size: 12.5px; color: var(--text-dim); margin-top: 12px; font-family: var(--font-mono);">
    @if ($lastEmail)
      Last email received {{ $lastEmail->created_at->diffForHumans() }}.
    @else
      No emails received yet.
    @endif
  </p>
</div>

<div class="glass-panel">
  <h2 style="font-size: 13px; font-family: var(--font-mono); text-transform: uppercase; color: var(--upwork-dark); font-weight: 700; margin-bottom: 8px;">3 · Real-Time Webhook (UpHunt & Vibeworker)</h2>
  <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 12px;">Paste this URL into UpHunt → Notification Settings → Webhook → Set.</p>
  <div style="display: flex; gap: 10px; align-items: center;">
    <input type="text" id="wh" readonly value="{{ rtrim(config('app.url'), '/') }}/api/hook/{{ $user->webhook_token }}" style="font-family: var(--font-mono); font-size: 13.5px; background: var(--bg-subtle);">
    <button class="btn btn-ghost btn-sm" onclick="copyVal('wh', this)" style="flex: none;">Copy Webhook</button>
  </div>
  <p style="font-size: 12.5px; color: var(--text-dim); margin-top: 6px;">Keep this URL private — anyone with this URL can send jobs to your account.</p>
</div>

<div class="glass-panel">
  <h2 style="font-size: 13px; font-family: var(--font-mono); text-transform: uppercase; color: var(--upwork-dark); font-weight: 700; margin-bottom: 8px;">4 · Telegram Alerts</h2>
  @if ($user->telegram_chat_id)
    <p style="font-size: 14.5px; color: var(--upwork-tint-text); font-weight: 600; margin-bottom: 14px;">✅ <b>Connected</b> — job alerts are streaming to your Telegram.</p>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
      <form method="POST" action="{{ route('settings.testTelegram') }}" style="display: inline;">
        @csrf
        <button class="btn btn-ghost btn-sm">Send Test Message 📲</button>
      </form>
      <a class="btn btn-ghost btn-sm" href="https://t.me/{{ config('services.telegram.bot_username') }}?start={{ $user->webhook_token }}" target="_blank" rel="noopener">Reconnect Bot</a>
    </div>
  @else
    <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 14px;">One tap: opens Telegram, press <b>Start</b>, and you are connected.</p>
    <a class="btn btn-sm" href="https://t.me/{{ config('services.telegram.bot_username') }}?start={{ $user->webhook_token }}" target="_blank" rel="noopener">📲 Connect Telegram</a>
  @endif
</div>

<div class="glass-panel">
  <h2 style="font-size: 13px; font-family: var(--font-mono); text-transform: uppercase; color: var(--text-muted); font-weight: 700; margin-bottom: 8px;">Plan & Usage</h2>
  @if ($user->plan === 'pro')
    <p style="font-size: 15px; color: var(--text-dark);">
      Current Plan: <b style="text-transform: uppercase; color: var(--upwork-green);">PRO</b> ·
      <span style="font-family: var(--font-mono); font-weight: 700;">{{ $user->letters_used }} / {{ $user->letters_quota }}</span> proposals used this month.
    </p>
  @elseif ($user->onTrial())
    <p style="font-size: 15px; color: var(--text-dark);">
      Current Plan: <b style="text-transform: uppercase; color: var(--upwork-green);">FREE TRIAL</b> —
      {{ (int) now()->diffInDays($user->trial_ends_at) }} days left (ends {{ $user->trial_ends_at->format('d M Y') }}) ·
      <span style="font-family: var(--font-mono); font-weight: 700;">{{ $user->letters_used }} / {{ $user->letters_quota }}</span> proposals used this month.
    </p>
  @else
    <p style="font-size: 15px; color: var(--red);">Trial ended. Contact support to keep generating proposals.</p>
  @endif
</div>
@endsection
