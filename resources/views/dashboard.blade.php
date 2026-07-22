@extends('layout')
@section('title', 'Job Inbox — FirstBid AI')
@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 20px;">
  <div>
    <h1 style="font-size: 24px; font-weight: 800; color: #fff; margin-bottom: 4px;">Job Inbox</h1>
    <p style="color: var(--text-muted); font-size: 13.5px;">
      @if($window === '24h') Showing real-time jobs from the last 24 hours.
      @elseif($window === '3d') Showing jobs from the last 3 days.
      @elseif($window === '7d') Showing jobs from the last 7 days.
      @else Showing all captured jobs.
      @endif
    </p>
  </div>

  <!-- Time Window Filters -->
  <div style="display: flex; gap: 6px; background: rgba(17, 25, 21, 0.9); padding: 4px; border-radius: 8px; border: 1px solid var(--border);">
    @foreach(['24h' => '24h', '3d' => '3 Days', '7d' => '7 Days', 'all' => 'All'] as $w => $label)
      <a href="{{ route('dashboard', array_filter(['window' => $w, 'status' => request('status')])) }}"
         style="font-family: var(--font-mono); font-size: 12px; padding: 5px 12px; border-radius: 6px; text-decoration: none; transition: all 0.2s ease; {{ $window === $w ? 'background: var(--emerald); color: #042f14; font-weight: 700;' : 'color: var(--text-muted);' }}">
        {{ $label }}
      </a>
    @endforeach
  </div>
</div>

<!-- Stats Grid -->
<div class="stat-grid">
  <div class="stat-card">
    <div class="val">{{ $stats['total'] }}</div>
    <div class="lbl">Jobs Received</div>
  </div>
  <div class="stat-card">
    <div class="val" style="color: var(--emerald-light);">{{ $stats['letters'] }}</div>
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
    <span>⚠️ Setup incomplete — finish your <a href="{{ route('settings') }}" style="color: #fff; text-decoration: underline;">Settings</a> profile to receive automated job alerts.</span>
  </div>
@endif

@forelse($jobs as $job)
  <details class="glass-panel" style="margin-bottom: 14px; padding: 0; overflow: hidden;">
    <summary style="list-style: none; cursor: pointer; padding: 16px 20px; display: flex; gap: 14px; align-items: center; flex-wrap: wrap; user-select: none;">
      @php $s = $job->uphunt_score; @endphp
      <span class="badge" style="font-size: 13px; padding: 4px 10px; font-weight: 700; font-family: var(--font-mono); {{ $s >= 8 ? 'background: rgba(16,185,129,0.2); color: #34d399; border: 1px solid rgba(16,185,129,0.4);' : ($s >= 6 ? 'background: rgba(245,158,11,0.2); color: #fcd34d; border: 1px solid rgba(245,158,11,0.4);' : 'background: rgba(255,255,255,0.06); color: var(--text-muted); border: 1px solid var(--border);') }}">
        {{ $s ?? '–' }}
      </span>

      <span style="font-weight: 600; color: #fff; flex: 1; min-width: 220px; font-size: 15px;">{{ $job->title }}</span>
      <span style="font-size: 12.5px; color: var(--text-muted); font-family: var(--font-mono);">{{ $job->budget_display }} · {{ $job->client_country ?? '?' }} · {{ $job->created_at->diffForHumans() }}</span>

      <span class="badge badge-{{ $job->status === 'ready_to_generate' ? 'pending' : ($job->status === 'notified' || $job->status === 'generated' ? 'notified' : ($job->status === 'failed' ? 'failed' : 'skipped')) }}">
        {{ $job->status_label }}
      </span>
    </summary>

    <div style="border-top: 1px solid var(--border); padding: 20px; background: rgba(0,0,0,0.2);">
      <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 12px;">
        ⭐ Client Score: {{ $job->client_score ?? '?' }} ({{ $job->client_hires ?? '?' }} hires)
        @if(!$job->payment_verified) · <span style="color: var(--red);">🚩 Payment Not Verified</span> @else · <span style="color: var(--emerald-light);">✅ Payment Verified</span> @endif
        @if($job->bid_suggestion) · <span style="color: #fcd34d;">💰 {{ $job->bid_suggestion }}</span> @endif
      </div>

      @if($job->estimated_budget || $job->estimated_duration)
        <div class="ai-estimator-box">
          <div class="ai-estimator-header">
            @if($job->estimated_budget)
              <div>💰 Recommended Bid: <span style="color: #fff; font-family: var(--font-mono);">{{ $job->estimated_budget }}</span></div>
            @endif
            @if($job->estimated_duration)
              <div>⏱️ Estimated Duration: <span style="color: #fff; font-family: var(--font-mono);">{{ $job->estimated_duration }}</span></div>
            @endif
          </div>
          @if($job->budget_reasoning)
            <div class="ai-estimator-strategy">
              💡 <b>AI Pricing Strategy:</b> {{ $job->budget_reasoning }}
            </div>
          @endif
          @if(!empty($job->task_breakdown))
            <div class="ai-task-list">
              📋 <b>Task Breakdown:</b>
              <ul>
                @foreach($job->task_breakdown as $t)
                  <li>{{ $t['task'] ?? 'Subtask' }}: ~{{ $t['hours'] ?? 0 }}h</li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>
      @endif

      @if($job->skip_reason)
        <p style="font-size: 12.5px; color: var(--text-muted); margin-top: 8px;">Reason: {{ $job->skip_reason }}</p>
      @endif

      @if($job->cover_letter)
        <div style="margin-top: 16px;">
          <h2 style="font-size: 12px; font-family: var(--font-mono); text-transform: uppercase; color: var(--emerald-light); margin-bottom: 6px;">Generated Cover Letter</h2>
          <div style="white-space: pre-wrap; background: rgba(17, 25, 21, 0.9); border: 1px solid var(--border); border-radius: 8px; padding: 14px; font-size: 13.5px; line-height: 1.6; color: #e5e7eb;" id="letter-{{ $job->id }}">{{ $job->cover_letter }}</div>
          <div style="margin-top: 10px; display: flex; gap: 8px; flex-wrap: wrap;">
            <button class="btn btn-ghost btn-sm" onclick="copyVal('letter-{{ $job->id }}', this)">Copy Letter</button>
            <form method="POST" action="{{ route('jobs.generate', $job->id) }}" style="display:inline">
              @csrf
              <button class="btn btn-ghost btn-sm" type="submit" onclick="this.innerText='Generating...'">Regenerate Proposal</button>
            </form>
            @if($job->job_url)<a class="btn btn-sm" href="{{ $job->job_url }}" target="_blank" rel="noopener">Open Job on Upwork ↗</a>@endif
          </div>
        </div>
      @elseif($job->status === 'ready_to_generate')
        <div style="margin-top: 14px; display: flex; gap: 10px;">
          <form method="POST" action="{{ route('jobs.generate', $job->id) }}">
            @csrf
            <button class="btn btn-sm" type="submit" onclick="this.innerText='Generating Proposal...'">✨ Generate Proposal</button>
          </form>
          @if($job->job_url)<a class="btn btn-ghost btn-sm" href="{{ $job->job_url }}" target="_blank" rel="noopener">Open Job on Upwork ↗</a>@endif
        </div>
      @elseif($job->job_url)
        <div style="margin-top: 12px;">
          <a class="btn btn-ghost btn-sm" href="{{ $job->job_url }}" target="_blank" rel="noopener">Open Job on Upwork ↗</a>
        </div>
      @endif

      @if(!empty($job->question_answers))
        <div style="margin-top: 16px; border-top: 1px solid var(--border); padding-top: 14px;">
          <h2 style="font-size: 12px; font-family: var(--font-mono); text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Screening Answers</h2>
          @foreach($job->question_answers as $a)
            @php
              $q = collect($job->screening_questions)->firstWhere('position', $a['position'] ?? -1);
            @endphp
            <div style="font-weight: 600; font-size: 13px; color: var(--text-main); margin-top: 8px;">❓ {{ $q['question'] ?? 'Question' }}</div>
            <div style="background: rgba(17, 25, 21, 0.9); border: 1px solid var(--border); border-radius: 6px; padding: 10px; font-size: 13px; color: #d1d5db; margin-top: 4px;" id="qa-{{ $job->id }}-{{ $a['position'] ?? 0 }}">{{ $a['answer'] ?? '' }}</div>
            <button class="btn btn-ghost btn-sm" style="margin-top: 6px;" onclick="copyVal('qa-{{ $job->id }}-{{ $a['position'] ?? 0 }}', this)">Copy Answer</button>
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

<div style="margin-top: 24px;">
  {{ $jobs->links('partials.pagination') }}
</div>
@endsection
