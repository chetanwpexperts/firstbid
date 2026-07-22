@extends('layout')

@section('title', $job->title . ' — FirstBid AI')

@section('content')
<div style="margin-bottom: 16px;">
  <a href="{{ route('dashboard') }}" style="color: var(--text-muted); font-size: 13.5px;">← Back to Job Inbox</a>
</div>

<div class="glass-panel">
  <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px; margin-bottom: 12px;">
    <h1 style="font-size: 24px; font-weight: 800; color: #fff; flex: 1; min-width: 260px;">{{ $job->title }}</h1>
    @if ($job->job_url)
      <a class="btn btn-sm" href="{{ $job->job_url }}" target="_blank" rel="noopener">Open Job on Upwork ↗</a>
    @endif
  </div>

  <div style="font-size: 13.5px; color: var(--text-muted); display: flex; gap: 12px; flex-wrap: wrap; align-items: center; margin-bottom: 16px;">
    <span class="badge badge-notified">Score {{ $job->uphunt_score ?? '—' }}</span>
    <span>{{ $job->budget_display }}</span>
    <span>Client: {{ $job->client_country ?? 'Unknown' }}</span>
    <span>⭐ {{ $job->client_score ?? '—' }} ({{ $job->client_hires ?? '—' }} hires)</span>
    @if(!$job->payment_verified)
      <span style="color: var(--red);">🚩 Payment Not Verified</span>
    @else
      <span style="color: var(--emerald-light);">✅ Payment Verified</span>
    @endif
  </div>

  @if($job->estimated_budget || $job->estimated_duration)
    <div class="ai-estimator-box" style="margin-bottom: 20px;">
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

  @if ($job->cover_letter)
    <div style="margin-top: 20px; border-top: 1px solid var(--border); padding-top: 16px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <h2 style="font-size: 13px; font-family: var(--font-mono); text-transform: uppercase; color: var(--emerald-light);">Cover Letter</h2>
        <button class="btn btn-ghost btn-sm" onclick="copyText('letter', this)">Copy Letter</button>
      </div>
      <div style="white-space: pre-wrap; background: rgba(17, 25, 21, 0.9); border: 1px solid var(--border); border-radius: 8px; padding: 16px; font-size: 14px; line-height: 1.65; color: #e5e7eb;" id="letter">{{ $job->cover_letter }}</div>
    </div>
  @else
    <div style="margin-top: 20px; border-top: 1px solid var(--border); padding-top: 16px; text-align: center;">
      <p style="color: var(--text-muted); margin-bottom: 14px;">No letter generated yet — generate one when you are ready to apply.</p>
      <form method="POST" action="{{ route('jobs.generate', $job->id) }}">
        @csrf
        <button class="btn" type="submit">✨ Generate Cover Letter & AI Budget</button>
      </form>
    </div>
  @endif

  @if (!empty($job->question_answers))
    <div style="margin-top: 24px; border-top: 1px solid var(--border); padding-top: 16px;">
      <h2 style="font-size: 13px; font-family: var(--font-mono); text-transform: uppercase; color: var(--text-muted); margin-bottom: 12px;">Screening Answers</h2>
      @foreach ($job->question_answers as $i => $qa)
        @php $q = collect($job->screening_questions)->firstWhere('position', $qa['position'] ?? -1); @endphp
        <div style="margin-bottom: 14px;">
          <div style="font-weight: 600; color: #fff; font-size: 13.5px; margin-bottom: 4px;">❓ {{ $q['question'] ?? 'Question' }}</div>
          <div style="background: rgba(17, 25, 21, 0.9); border: 1px solid var(--border); border-radius: 8px; padding: 12px; font-size: 13.5px; color: #d1d5db;" id="qa{{ $i }}">{{ $qa['answer'] ?? '' }}</div>
          <button class="btn btn-ghost btn-sm" style="margin-top: 6px;" onclick="copyText('qa{{ $i }}', this)">Copy Answer</button>
        </div>
      @endforeach
    </div>
  @endif

  <div style="margin-top: 24px; border-top: 1px solid var(--border); padding-top: 16px;">
    <h2 style="font-size: 13px; font-family: var(--font-mono); text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Full Job Description</h2>
    <div style="white-space: pre-wrap; background: rgba(0,0,0,0.3); border: 1px solid var(--border); border-radius: 8px; padding: 16px; font-size: 13.5px; color: var(--text-muted); line-height: 1.6;">{{ $job->description }}</div>
  </div>
</div>
@endsection
