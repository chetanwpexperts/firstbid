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

<!-- Section 1: 3 Opener Hook Options ("First 2 Lines" Optimizer) -->
@if(!empty($job->opener_hooks))
<div class="glass-panel" style="margin-bottom: 24px; padding: 28px;">
  <h2 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
    🎯 "First 2 Lines" Opener Hook Options
  </h2>
  <p style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 18px;">Upwork clients only see the first 2 lines in preview before clicking. Pick your preferred opening hook:</p>

  <div style="display: flex; flex-direction: column; gap: 12px;">
    @if(!empty($job->opener_hooks['problem_direct']))
      <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px; padding: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
          <span style="font-size: 12px; font-family: var(--font-mono); font-weight: 800; color: var(--upwork-green); text-transform: uppercase;">🛠️ Option 1: Problem-Direct Opener</span>
          <button class="btn btn-ghost btn-sm" onclick="copyVal('hook-pd', this)" style="padding: 3px 8px; font-size: 11.5px;">Copy Hook</button>
        </div>
        <div style="font-size: 14px; color: var(--text-dark); font-weight: 600;" id="hook-pd">{{ $job->opener_hooks['problem_direct'] }}</div>
      </div>
    @endif

    @if(!empty($job->opener_hooks['results_first']))
      <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px; padding: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
          <span style="font-size: 12px; font-family: var(--font-mono); font-weight: 800; color: var(--upwork-green); text-transform: uppercase;">📈 Option 2: Results & Metrics Opener</span>
          <button class="btn btn-ghost btn-sm" onclick="copyVal('hook-rf', this)" style="padding: 3px 8px; font-size: 11.5px;">Copy Hook</button>
        </div>
        <div style="font-size: 14px; color: var(--text-dark); font-weight: 600;" id="hook-rf">{{ $job->opener_hooks['results_first'] }}</div>
      </div>
    @endif

    @if(!empty($job->opener_hooks['fast_delivery']))
      <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px; padding: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
          <span style="font-size: 12px; font-family: var(--font-mono); font-weight: 800; color: var(--upwork-green); text-transform: uppercase;">⚡ Option 3: Fast Execution Opener</span>
          <button class="btn btn-ghost btn-sm" onclick="copyVal('hook-fd', this)" style="padding: 3px 8px; font-size: 11.5px;">Copy Hook</button>
        </div>
        <div style="font-size: 14px; color: var(--text-dark); font-weight: 600;" id="hook-fd">{{ $job->opener_hooks['fast_delivery'] }}</div>
      </div>
    @endif
  </div>
</div>
@endif

<!-- Section 2: AI Scope & Budget Breakdown Card -->
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

<!-- Section 3: Upwork Deposit Milestones Card -->
@if(!empty($job->milestones))
<div class="glass-panel" style="margin-bottom: 24px; padding: 28px;">
  <h2 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 12px;">
    📅 Upwork Deposit Milestones
  </h2>
  <p style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 18px;">Paste these milestone phases into Upwork's milestone breakdown section:</p>

  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 14px;">
    @foreach($job->milestones as $m)
      <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px; padding: 18px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
          <span style="font-family: var(--font-mono); font-size: 12px; font-weight: 800; color: var(--upwork-green); uppercase;">{{ $m['phase'] ?? 'Milestone' }}</span>
          <span style="font-family: var(--font-mono); font-weight: 800; color: var(--text-dark);">{{ $m['amount'] ?? '' }}</span>
        </div>
        <div style="font-weight: 700; color: var(--text-dark); font-size: 14.5px; margin-bottom: 4px;">{{ $m['title'] ?? '' }}</div>
        <div style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 8px;">⏱️ {{ $m['days'] ?? '' }}</div>
        <div style="font-size: 13px; color: var(--text-main); line-height: 1.5;">📦 {{ $m['deliverables'] ?? '' }}</div>
      </div>
    @endforeach
  </div>
</div>
@endif

<!-- Section 4: Generated Cover Letter Card -->
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

<!-- Section 5: Screening Questions Card -->
@if (!empty($job->question_answers))
<div class="glass-panel" style="margin-bottom: 24px; padding: 28px;">
  <h2 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 18px;">
    📝 Screening Questions & AI Draft Answers
  </h2>

  <div style="display: flex; flex-direction: column; gap: 14px;">
    @foreach ($job->question_answers as $i => $qa)
      @php $q = collect($job->screening_questions)->firstWhere('position', $qa['position'] ?? -1); @endphp
      <div class="screening-card">
        <div class="screening-header">
          <div class="screening-question">❓ {{ $q['question'] ?? 'Question' }}</div>
          <button class="btn btn-ghost btn-sm" onclick="copyText('qa{{ $i }}', this)" style="padding: 4px 10px; font-size: 12px;">Copy Answer</button>
        </div>
        <div class="screening-answer" id="qa{{ $i }}">{{ $qa['answer'] ?? '' }}</div>
      </div>
    @endforeach
  </div>
</div>
@endif

<!-- Section 6: Full Job Description Card -->
<div class="glass-panel" style="padding: 28px;">
  <h2 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 14px;">
    📋 Original Job Posting Details
  </h2>
  <div style="white-space: pre-wrap; background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px; padding: 20px; font-size: 14px; color: var(--text-muted); line-height: 1.65;">{{ $job->description }}</div>
</div>
@endsection
