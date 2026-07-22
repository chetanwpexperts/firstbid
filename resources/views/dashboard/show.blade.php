@extends('layout')

@section('title', $job->title . ' — FirstBidIn AI Workspace')

@section('content')
<div style="margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
  <a href="{{ route('dashboard') }}" style="color: var(--text-muted); font-weight: 600; font-size: 13.5px; display: inline-flex; align-items: center; gap: 6px;">
    ← Back to Job Inbox
  </a>
  @if ($job->job_url)
    <a class="btn btn-sm" href="{{ $job->job_url }}" target="_blank" rel="noopener">
      Open Job on Upwork ↗
    </a>
  @endif
</div>

<!-- 2-COLUMN WORKSPACE LAYOUT -->
<div class="workspace-grid">
  
  <!-- LEFT COLUMN: AI PROPOSAL WORKSPACE (TABS) -->
  <div>
    <!-- Tab Navigation Bar -->
    <div class="tab-nav">
      <button type="button" class="tab-btn active" onclick="switchTab('tab-hooks', this)">
        🎯 Opener Hooks
      </button>
      <button type="button" class="tab-btn" onclick="switchTab('tab-letter', this)">
        ✍️ Cover Letter
      </button>
      <button type="button" class="tab-btn" onclick="switchTab('tab-scope', this)">
        📊 AI Scope & Milestones
      </button>
      @if (!empty($job->question_answers))
        <button type="button" class="tab-btn" onclick="switchTab('tab-qa', this)">
          📝 Screening Q&A
        </button>
      @endif
    </div>

    <!-- TAB 1: OPENER HOOKS -->
    <div class="tab-panel active" id="tab-hooks">
      <div class="glass-panel" style="padding: 24px;">
        <h2 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">
          🎯 "First 2 Lines" Opener Hook Options
        </h2>
        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">
          Upwork clients see the first 2 lines in preview before clicking. Pick your preferred opening hook:
        </p>

        @if(!empty($job->opener_hooks))
          @if(!empty($job->opener_hooks['problem_direct']))
            <div class="hook-card">
              <div class="hook-header">
                <span class="hook-title">🛠️ Option 1: Problem-Direct Opener</span>
                <button class="btn btn-ghost btn-sm" onclick="copyVal('hook-pd', this)">Copy Hook</button>
              </div>
              <div class="hook-body" id="hook-pd">{{ $job->opener_hooks['problem_direct'] }}</div>
            </div>
          @endif

          @if(!empty($job->opener_hooks['results_first']))
            <div class="hook-card">
              <div class="hook-header">
                <span class="hook-title">📈 Option 2: Results & Metrics Opener</span>
                <button class="btn btn-ghost btn-sm" onclick="copyVal('hook-rf', this)">Copy Hook</button>
              </div>
              <div class="hook-body" id="hook-rf">{{ $job->opener_hooks['results_first'] }}</div>
            </div>
          @endif

          @if(!empty($job->opener_hooks['fast_delivery']))
            <div class="hook-card">
              <div class="hook-header">
                <span class="hook-title">⚡ Option 3: Fast Execution Opener</span>
                <button class="btn btn-ghost btn-sm" onclick="copyVal('hook-fd', this)">Copy Hook</button>
              </div>
              <div class="hook-body" id="hook-fd">{{ $job->opener_hooks['fast_delivery'] }}</div>
            </div>
          @endif
        @else
          <div style="text-align: center; padding: 30px 10px; color: var(--text-muted); font-size: 14px;">
            Generate an AI proposal to unlock 3 distinct "First 2 Lines" opener hook options.
          </div>
        @endif
      </div>
    </div>

    <!-- TAB 2: COVER LETTER -->
    <div class="tab-panel" id="tab-letter">
      <div class="glass-panel" style="padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
          <h2 style="font-size: 16px; font-weight: 800; color: var(--text-dark);">
            ✍️ Generated Cover Letter
          </h2>
          @if ($job->cover_letter)
            <div style="display: flex; gap: 8px;">
              <button class="btn btn-ghost btn-sm" onclick="copyText('letter-body', this)">Copy Proposal</button>
              <form method="POST" action="{{ route('jobs.generate', $job->id) }}" style="display: inline;">
                @csrf
                <button class="btn btn-ghost btn-sm" type="submit">Regenerate AI Proposal</button>
              </form>
            </div>
          @endif
        </div>

        @if ($job->cover_letter)
          <div style="white-space: pre-wrap; background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px; padding: 20px; font-size: 14px; line-height: 1.65; color: var(--text-dark);" id="letter-body">{{ $job->cover_letter }}</div>
        @else
          <div style="text-align: center; padding: 36px 20px; background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px;">
            <p style="color: var(--text-muted); margin-bottom: 16px; font-size: 14.5px;">No proposal written yet for this job alert.</p>
            <form method="POST" action="{{ route('jobs.generate', $job->id) }}">
              @csrf
              <button class="btn" type="submit" style="padding: 11px 22px; font-size: 14.5px;">✨ Generate Proposal & AI Scope</button>
            </form>
          </div>
        @endif
      </div>
    </div>

    <!-- TAB 3: AI SCOPE & MILESTONES -->
    <div class="tab-panel" id="tab-scope">
      @if($job->estimated_budget || $job->estimated_duration || !empty($job->task_breakdown))
        <div class="ai-estimator-box" style="margin-top: 0; margin-bottom: 20px;">
          <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
            <span style="font-size: 18px;">🤖</span>
            <h2 style="font-size: 15px; font-weight: 800; color: var(--upwork-deep); text-transform: uppercase;">AI Scope & Budget Calculator</h2>
          </div>

          <div class="ai-estimator-header" style="margin-bottom: 14px;">
            @if($job->estimated_budget)
              <div style="background: #ffffff; border: 1px solid var(--upwork-tint-border); padding: 8px 14px; border-radius: 6px; font-size: 13.5px;">
                💰 Recommended Bid: <span style="color: var(--upwork-dark); font-family: var(--font-mono); font-weight: 800;">{{ $job->estimated_budget }}</span>
              </div>
            @endif
            @if($job->estimated_duration)
              <div style="background: #ffffff; border: 1px solid var(--upwork-tint-border); padding: 8px 14px; border-radius: 6px; font-size: 13.5px;">
                ⏱️ Estimated Duration: <span style="color: var(--upwork-dark); font-family: var(--font-mono); font-weight: 800;">{{ $job->estimated_duration }}</span>
              </div>
            @endif
          </div>

          @if($job->budget_reasoning)
            <div class="ai-estimator-strategy">
              <b>💡 Scope & Pricing Strategy:</b> {{ $job->budget_reasoning }}
            </div>
          @endif

          @if(!empty($job->task_breakdown))
            <div>
              <div style="font-size: 12px; font-weight: 700; color: var(--upwork-tint-text); text-transform: uppercase; margin-bottom: 8px; font-family: var(--font-mono);">
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

      @if(!empty($job->milestones))
        <div class="glass-panel" style="padding: 24px;">
          <h2 style="font-size: 15px; font-weight: 800; color: var(--text-dark); margin-bottom: 10px;">
            📅 Upwork Deposit Milestones
          </h2>
          <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 14px;">Paste these milestone phases into Upwork's milestone breakdown section:</p>

          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px;">
            @foreach($job->milestones as $m)
              <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 8px; padding: 14px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                  <span style="font-family: var(--font-mono); font-size: 11.5px; font-weight: 800; color: var(--upwork-green); uppercase;">{{ $m['phase'] ?? 'Milestone' }}</span>
                  <span style="font-family: var(--font-mono); font-weight: 800; color: var(--text-dark); font-size: 13px;">{{ $m['amount'] ?? '' }}</span>
                </div>
                <div style="font-weight: 700; color: var(--text-dark); font-size: 13.5px; margin-bottom: 4px;">{{ $m['title'] ?? '' }}</div>
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 6px;">⏱️ {{ $m['days'] ?? '' }}</div>
                <div style="font-size: 12.5px; color: var(--text-main); line-height: 1.45;">📦 {{ $m['deliverables'] ?? '' }}</div>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>

    <!-- TAB 4: SCREENING QUESTIONS -->
    @if (!empty($job->question_answers))
      <div class="tab-panel" id="tab-qa">
        <div class="glass-panel" style="padding: 24px;">
          <h2 style="font-size: 15px; font-weight: 800; color: var(--text-dark); margin-bottom: 14px;">
            📝 Screening Questions & AI Draft Answers
          </h2>

          <div style="display: flex; flex-direction: column; gap: 12px;">
            @foreach ($job->question_answers as $i => $qa)
              @php $q = collect($job->screening_questions)->firstWhere('position', $qa['position'] ?? -1); @endphp
              <div class="screening-card">
                <div class="screening-header">
                  <div class="screening-question">❓ {{ $q['question'] ?? 'Question' }}</div>
                  <button class="btn btn-ghost btn-sm" onclick="copyText('qa-ans-{{ $i }}', this)">Copy Answer</button>
                </div>
                <div class="screening-answer" id="qa-ans-{{ $i }}">{{ $qa['answer'] ?? '' }}</div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endif
  </div>

  <!-- RIGHT COLUMN: STICKY JOB DETAILS SIDEBAR -->
  <div class="workspace-sidebar">
    <div class="glass-panel" style="padding: 22px;">
      <h1 style="font-size: 18px; font-weight: 800; color: var(--text-dark); line-height: 1.35; margin-bottom: 12px;">{{ $job->title }}</h1>

      <div style="display: flex; flex-direction: column; gap: 8px; font-size: 13px; color: var(--text-muted); border-bottom: 1px solid var(--border); padding-bottom: 14px; margin-bottom: 14px;">
        @php $s = $job->uphunt_score; @endphp
        <div>
          <span class="badge" style="font-size: 12px; padding: 4px 10px; font-weight: 800; background: var(--upwork-tint); color: var(--upwork-tint-text); border: 1px solid var(--upwork-tint-border);">
            MATCH SCORE {{ $s ?? '—' }}
          </span>
        </div>
        <div>💰 Budget: <span style="font-family: var(--font-mono); font-weight: 700; color: var(--text-dark);">{{ $job->budget_display }}</span></div>
        <div>📍 Location: <span style="font-weight: 600; color: var(--text-dark);">{{ $job->client_country ?? 'Unknown' }}</span></div>
        <div>⭐ Client Rating: <span style="font-weight: 600; color: var(--text-dark);">{{ $job->client_score ?? '—' }} ({{ $job->client_hires ?? '—' }} hires)</span></div>
        <div>
          @if(!$job->payment_verified)
            <span style="color: var(--red); font-weight: 600;">🚩 Payment Not Verified</span>
          @else
            <span style="color: var(--upwork-tint-text); font-weight: 600;">✅ Payment Verified</span>
          @endif
        </div>
      </div>

      <h3 style="font-size: 13px; font-family: var(--font-mono); text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px; font-weight: 700;">Job Description:</h3>
      <div style="white-space: pre-wrap; font-size: 13px; color: var(--text-main); line-height: 1.55; max-height: 420px; overflow-y: auto; background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 8px; padding: 14px;">{{ $job->description }}</div>
    </div>
  </div>

</div>
@endsection
