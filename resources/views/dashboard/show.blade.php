@extends('layout')

@section('title', $job->title . ' — FirstBid AI')

@section('content')
<div style="margin-bottom: 20px;">
  <a href="{{ route('dashboard') }}" style="color: var(--text-muted); font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 6px;">
    ← Back to Job Inbox
  </a>
</div>

<!-- Main Job Header Card -->
<div class="glass-panel" style="margin-bottom: 24px; padding: 28px;">
  <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; margin-bottom: 16px;">
    <h1 style="font-size: 26px; font-weight: 800; color: var(--text-dark); flex: 1; min-width: 280px; line-height: 1.3;">{{ $job->title }}</h1>
    @if ($job->job_url)
      <a class="btn" href="{{ $job->job_url }}" target="_blank" rel="noopener" style="padding: 10px 20px; font-size: 14.5px;">
        Open Job on Upwork ↗
      </a>
    @endif
  </div>

  <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center; font-size: 13.5px; color: var(--text-muted);">
    @php $s = $job->uphunt_score; @endphp
    <span class="badge" style="font-size: 13px; padding: 5px 12px; font-weight: 800; background: var(--upwork-tint); color: var(--upwork-tint-text); border: 1px solid var(--upwork-tint-border);">
      MATCH SCORE {{ $s ?? '—' }}
    </span>

    <span style="font-family: var(--font-mono); font-weight: 700; color: var(--text-dark);">{{ $job->budget_display }}</span>
    <span>•</span>
    <span>📍 {{ $job->client_country ?? 'Unknown Location' }}</span>
    <span>•</span>
    <span>⭐ {{ $job->client_score ?? '—' }} ({{ $job->client_hires ?? '—' }} hires)</span>
    <span>•</span>
    @if(!$job->payment_verified)
      <span style="color: var(--red); font-weight: 600;">🚩 Payment Not Verified</span>
    @else
      <span style="color: var(--upwork-tint-text); font-weight: 600;">✅ Payment Verified</span>
    @endif
  </div>
</div>

<!-- Section 1: AI Budget & Scope Estimator Card -->
@if($job->estimated_budget || $job->estimated_duration || !empty($job->task_breakdown))
<div class="ai-estimator-box" style="margin-bottom: 24px;">
  <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px;">
    <span style="font-size: 20px;">🤖</span>
    <h2 style="font-size: 16px; font-weight: 800; color: var(--upwork-deep); text-transform: uppercase; letter-spacing: 0.04em;">AI Scope & Budget Breakdown</h2>
  </div>

  <div class="ai-estimator-header" style="margin-bottom: 16px;">
    @if($job->estimated_budget)
      <div style="background: #ffffff; border: 1px solid var(--upwork-tint-border); padding: 10px 16px; border-radius: 8px;">
        💰 Recommended Bid: <span style="color: var(--upwork-dark); font-family: var(--font-mono); font-weight: 800;">{{ $job->estimated_budget }}</span>
      </div>
    @endif
    @if($job->estimated_duration)
      <div style="background: #ffffff; border: 1px solid var(--upwork-tint-border); padding: 10px 16px; border-radius: 8px;">
        ⏱️ Estimated Duration: <span style="color: var(--upwork-dark); font-family: var(--font-mono); font-weight: 800;">{{ $job->estimated_duration }}</span>
      </div>
    @endif
  </div>

  @if($job->budget_reasoning)
    <div class="ai-estimator-strategy">
      <b>💡 Pricing & Scope Strategy:</b>
      <div style="margin-top: 4px; color: var(--text-dark);">{{ $job->budget_reasoning }}</div>
    </div>
  @endif

  @if(!empty($job->task_breakdown))
    <div style="margin-top: 16px;">
      <div style="font-size: 13px; font-weight: 700; color: var(--upwork-tint-text); text-transform: uppercase; margin-bottom: 8px; font-family: var(--font-mono);">
        📋 Subtask Effort Breakdown:
      </div>
      <div class="ai-task-grid">
        @foreach($job->task_breakdown as $t)
          <div class="ai-task-card">
            <span>{{ $t['task'] ?? 'Subtask' }}</span>
            <span class="hrs">~{{ $t['hours'] ?? 0 }} hrs</span>
          </div>
        @endforeach
      </div>
    </div>
  @endif
</div>
@endif

<!-- Section 2: Generated Cover Letter Card -->
<div class="glass-panel" style="margin-bottom: 24px; padding: 28px;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
    <h2 style="font-size: 16px; font-weight: 800; color: var(--text-dark); display: flex; align-items: center; gap: 8px;">
      ✍️ Generated Cover Letter
    </h2>
    @if ($job->cover_letter)
      <div style="display: flex; gap: 10px;">
        <button class="btn btn-ghost btn-sm" onclick="copyText('letter', this)">Copy Proposal</button>
        <form method="POST" action="{{ route('jobs.generate', $job->id) }}" style="display: inline;">
          @csrf
          <button class="btn btn-ghost btn-sm" type="submit">Regenerate AI Proposal</button>
        </form>
      </div>
    @endif
  </div>

  @if ($job->cover_letter)
    <div style="white-space: pre-wrap; background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px; padding: 22px; font-size: 14.5px; line-height: 1.7; color: var(--text-dark); font-family: var(--font-sans);" id="letter">{{ $job->cover_letter }}</div>
  @else
    <div style="text-align: center; padding: 36px 20px; background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px;">
      <p style="color: var(--text-muted); margin-bottom: 16px; font-size: 15px;">No proposal written yet for this job alert.</p>
      <form method="POST" action="{{ route('jobs.generate', $job->id) }}">
        @csrf
        <button class="btn" type="submit" style="padding: 12px 24px; font-size: 15px;">✨ Generate Cover Letter & AI Scope</button>
      </form>
    </div>
  @endif
</div>

<!-- Section 3: Screening Questions Card -->
@if (!empty($job->question_answers))
<div class="glass-panel" style="margin-bottom: 24px; padding: 28px;">
  <h2 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 18px;">
    📝 Screening Questions & AI Draft Answers
  </h2>

  <div style="display: flex; flex-direction: column; gap: 18px;">
    @foreach ($job->question_answers as $i => $qa)
      @php $q = collect($job->screening_questions)->firstWhere('position', $qa['position'] ?? -1); @endphp
      <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px; padding: 18px;">
        <div style="font-weight: 700; color: var(--text-dark); font-size: 14.5px; margin-bottom: 8px;">❓ {{ $q['question'] ?? 'Question' }}</div>
        <div style="white-space: pre-wrap; font-size: 14px; color: var(--text-main); line-height: 1.65; margin-bottom: 12px;" id="qa{{ $i }}">{{ $qa['answer'] ?? '' }}</div>
        <button class="btn btn-ghost btn-sm" onclick="copyText('qa{{ $i }}', this)">Copy Answer</button>
      </div>
    @endforeach
  </div>
</div>
@endif

<!-- Section 4: Full Job Description Card -->
<div class="glass-panel" style="padding: 28px;">
  <h2 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 14px;">
    📋 Original Job Posting Details
  </h2>
  <div style="white-space: pre-wrap; background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px; padding: 20px; font-size: 14px; color: var(--text-muted); line-height: 1.65;">{{ $job->description }}</div>
</div>
@endsection
