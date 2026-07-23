@extends('layout')

@section('title', 'Job Inbox — FirstBid AI')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 24px;">
  <div>
    <h1 style="font-size: 24px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">Job Inbox</h1>
    <p style="color: var(--text-muted); font-size: 14px;">
      Showing real-time verified jobs from the last 24 hours.
    </p>
  </div>

  <!-- Live 24h Feed Badge -->
  <div style="display: flex; align-items: center; gap: 8px; background: var(--upwork-tint); border: 1px solid var(--upwork-tint-border); padding: 6px 14px; border-radius: 20px; box-shadow: 0 2px 8px rgba(20, 168, 0, 0.08);">
    <div class="status-pulse-dot"></div>
    <span style="font-family: var(--font-mono); font-size: 12.5px; font-weight: 800; color: var(--upwork-tint-text); text-transform: uppercase;">Live 24h Feed</span>
  </div>
</div>

<!-- Stats Grid -->
<div class="stat-grid">
  <div class="stat-card">
    <div class="val">{{ $stats['total'] }}</div>
    <div class="lbl">Jobs Received</div>
  </div>
  <div class="stat-card">
    <div class="val" style="color: var(--upwork-green);">{{ $stats['letters'] }}</div>
    <div class="lbl">Proposals Written</div>
  </div>
  <div class="stat-card">
    <div class="val" style="color: var(--amber);">{{ $stats['skipped'] }}</div>
    <div class="lbl">Filtered Out</div>
  </div>
  <div class="stat-card">
    <div class="val">{{ $stats['quota'] }}</div>
    <div class="lbl">Letters Left</div>
  </div>
</div>

@if(!$user->telegram_chat_id || !$user->proposal_profile)
  <div class="flash-toast err">
    <span>⚠️ Setup incomplete — finish your <a href="{{ route('settings') }}" style="color: inherit; text-decoration: underline;">Settings</a> profile to receive automated job alerts.</span>
  </div>
@endif

<!-- Job List Container with Selective Blur -->
<div class="job-list">
@forelse($jobs as $job)
  <details class="glass-panel job-card" style="margin-bottom: 0; padding: 0; overflow: hidden;">
    <summary style="list-style: none; cursor: pointer; padding: 18px 24px; display: flex; gap: 16px; align-items: center; flex-wrap: wrap; user-select: none;">
      @php $s = $job->uphunt_score; @endphp
      <span class="badge" style="font-size: 13px; padding: 5px 12px; font-weight: 800; font-family: var(--font-mono); {{ $s >= 8 ? 'background: var(--upwork-tint); color: var(--upwork-tint-text); border: 1px solid var(--upwork-tint-border);' : ($s >= 6 ? 'background: var(--amber-tint); color: var(--amber); border: 1px solid var(--amber-border);' : 'background: #f1f5f9; color: var(--text-muted); border: 1px solid var(--border);') }}">
        SCORE {{ $s ?? '–' }}
      </span>

      <span style="font-weight: 700; color: var(--text-dark); flex: 1; min-width: 240px; font-size: 16px;">{{ $job->title }}</span>
      <span style="font-size: 13px; color: var(--text-muted); font-family: var(--font-mono);">{{ $job->budget_display }} · {{ $job->client_country ?? '?' }} · {{ $job->created_at->diffForHumans() }}</span>

      <span class="badge badge-{{ $job->status === 'ready_to_generate' ? 'pending' : ($job->status === 'notified' || $job->status === 'generated' ? 'notified' : ($job->status === 'failed' ? 'failed' : 'skipped')) }}">
        {{ $job->status_label }}
      </span>
    </summary>

    <div style="border-top: 1px solid var(--border); padding: 24px; background: var(--bg-subtle);">
      <div style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 14px; font-weight: 500;">
        ⭐ Client Score: {{ $job->client_score ?? '?' }} ({{ $job->client_hires ?? '?' }} hires)
        @if(!$job->payment_verified) · <span style="color: var(--red); font-weight: 600;">🚩 Payment Not Verified</span> @else · <span style="color: var(--upwork-tint-text); font-weight: 600;">✅ Payment Verified</span> @endif
        @if($job->bid_suggestion) · <span style="color: var(--amber); font-weight: 600;">💰 {{ $job->bid_suggestion }}</span> @endif
      </div>

      @if($job->estimated_budget || $job->estimated_duration)
        <div class="ai-estimator-box">
          <div class="ai-estimator-header">
            @if($job->estimated_budget)
              <div>💰 Recommended Bid: <span style="color: var(--upwork-dark); font-family: var(--font-mono); font-weight: 800;">{{ $job->estimated_budget }}</span></div>
            @endif
            @if($job->estimated_duration)
              <div>⏱️ Estimated Duration: <span style="color: var(--upwork-dark); font-family: var(--font-mono); font-weight: 800;">{{ $job->estimated_duration }}</span></div>
            @endif
          </div>
          @if($job->budget_reasoning)
            <div class="ai-estimator-strategy">
              💡 <b>AI Scope Strategy:</b> {{ $job->budget_reasoning }}
            </div>
          @endif
          @if(!empty($job->task_breakdown))
            <div style="margin-top: 14px;">
              <div style="font-size: 12.5px; font-weight: 700; color: var(--upwork-tint-text); text-transform: uppercase; font-family: var(--font-mono); margin-bottom: 8px;">📋 Task Breakdown:</div>
              <div class="ai-task-grid">
                @foreach($job->task_breakdown as $t)
                  <div class="ai-task-card">
                    <span>{{ $t['task'] ?? 'Subtask' }}</span>
                    <span class="hrs">~{{ $t['hours'] ?? 0 }}h</span>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>
      @endif

      @if($job->skip_reason)
        <p style="font-size: 13px; color: var(--text-muted); margin-top: 8px;">Reason: {{ $job->skip_reason }}</p>
      @endif

      @if($job->cover_letter)
        <div style="margin-top: 18px;">
          <h2 style="font-size: 12.5px; font-family: var(--font-mono); text-transform: uppercase; color: var(--upwork-dark); margin-bottom: 8px; font-weight: 700;">Generated Cover Letter</h2>
          <div style="white-space: pre-wrap; background: #ffffff; border: 1px solid var(--border); border-radius: 10px; padding: 18px; font-size: 14px; line-height: 1.65; color: var(--text-dark);" id="letter-{{ $job->id }}">{{ $job->cover_letter }}</div>
          <div style="margin-top: 12px; display: flex; gap: 10px; flex-wrap: wrap;">
            <button class="btn btn-ghost btn-sm" onclick="copyVal('letter-{{ $job->id }}', this)">Copy Proposal</button>
            <a class="btn btn-ghost btn-sm" href="{{ route('jobs.show', $job->id) }}">View Full Details</a>
            @if($job->job_url)<a class="btn btn-sm" href="{{ $job->job_url }}" target="_blank" rel="noopener">Open Job on Upwork ↗</a>@endif
          </div>
        </div>
      @elseif($job->status === 'ready_to_generate')
        <div style="margin-top: 16px; display: flex; gap: 10px;">
          <form method="POST" action="{{ route('jobs.generate', $job->id) }}">
            @csrf
            <button class="btn btn-sm" type="submit">✨ Generate Proposal & AI Scope</button>
          </form>
          <a class="btn btn-ghost btn-sm" href="{{ route('jobs.show', $job->id) }}">View Full Details</a>
          @if($job->job_url)<a class="btn btn-ghost btn-sm" href="{{ $job->job_url }}" target="_blank" rel="noopener">Open Job on Upwork ↗</a>@endif
        </div>
      @elseif($job->job_url)
        <div style="margin-top: 14px; display: flex; gap: 10px;">
          <a class="btn btn-ghost btn-sm" href="{{ route('jobs.show', $job->id) }}">View Full Details</a>
          <a class="btn btn-ghost btn-sm" href="{{ $job->job_url }}" target="_blank" rel="noopener">Open Job on Upwork ↗</a>
        </div>
      @endif

      @if(!empty($job->question_answers))
        <div style="margin-top: 24px; border-top: 1px solid var(--border); padding-top: 18px;">
          <h2 style="font-size: 13px; font-weight: 800; color: var(--text-dark); margin-bottom: 14px;">
            📝 Screening Questions & Draft Answers
          </h2>
          @foreach($job->question_answers as $a)
            @php
              $q = collect($job->screening_questions)->firstWhere('position', $a['position'] ?? -1);
            @endphp
            <div class="screening-card">
              <div class="screening-header">
                <div class="screening-question">❓ {{ $q['question'] ?? 'Question' }}</div>
                <button class="btn btn-ghost btn-sm" onclick="copyVal('qa-{{ $job->id }}-{{ $a['position'] ?? 0 }}', this)" style="padding: 4px 10px; font-size: 12px;">Copy Answer</button>
              </div>
              <div class="screening-answer" id="qa-{{ $job->id }}-{{ $a['position'] ?? 0 }}">{{ $a['answer'] ?? '' }}</div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </details>
@empty
  <div class="glass-panel" style="text-align: center; color: var(--text-muted); padding: 50px 20px;">
    No jobs captured in this time window. Once your webhook URL is set in UpHunt or RSS, matching jobs will appear here automatically.
  </div>
@endforelse
</div>

<div style="margin-top: 24px;">
  {{ $jobs->links('partials.pagination') }}
</div>
@endsection
