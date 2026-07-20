@extends('layout')
@section('title', 'Jobs — FirstBid')
@section('content')
<h1>Job inbox</h1>

<div class="statgrid">
  <div class="stat"><div class="n">{{ $stats['total'] }}</div><div class="l">jobs received</div></div>
  <div class="stat"><div class="n">{{ $stats['letters'] }}</div><div class="l">letters written</div></div>
  <div class="stat"><div class="n">{{ $stats['skipped'] }}</div><div class="l">filtered out</div></div>
  <div class="stat"><div class="n">{{ $stats['quota'] }}</div><div class="l">letters left</div></div>
</div>

@if(!$user->telegram_chat_id || !$user->proposal_profile)
  <div class="flash err">
    Setup incomplete — finish your <a href="{{ route('settings') }}">Settings</a>
    (profile @if(!$user->telegram_chat_id) and Telegram @endif) to start receiving letters.
  </div>
@endif

@forelse($jobs as $job)
  <details class="job">
    <summary>
      @php $s = $job->uphunt_score; @endphp
      <span class="score {{ $s >= 8 ? 's-hi' : ($s >= 6 ? 's-mid' : 's-low') }}">{{ $s ?? '–' }}</span>
      <span class="t">{{ $job->title }}</span>
      <span class="meta">{{ $job->budget_display }} · {{ $job->client_country ?? '?' }} · {{ $job->created_at->diffForHumans() }}</span>
      <span class="badge {{ $job->status }}">{{ $job->status }}</span>
    </summary>
    <div class="body">
      <div class="flagline">
        ⭐ {{ $job->client_score ?? '?' }} ({{ $job->client_hires ?? '?' }} hires)
        @if(!$job->payment_verified) · 🚩 payment not verified @endif
        @if($job->bid_suggestion) · 💰 {{ $job->bid_suggestion }} @endif
      </div>

      @if($job->skip_reason)
        <p class="help">Reason: {{ $job->skip_reason }}</p>
      @endif

      @if($job->cover_letter)
        <h2 style="margin-top:14px">Cover letter</h2>
        <div class="letter" id="letter-{{ $job->id }}">{{ $job->cover_letter }}</div>
        <div style="margin-top:8px;display:flex;gap:8px">
          <button class="btn ghost sm" onclick="copyVal('letter-{{ $job->id }}', this)">Copy letter</button>
          @if($job->job_url)<a class="btn sm" href="{{ $job->job_url }}" target="_blank" rel="noopener">Open job on Upwork ↗</a>@endif
        </div>
      @elseif($job->job_url)
        <div style="margin-top:10px"><a class="btn ghost sm" href="{{ $job->job_url }}" target="_blank" rel="noopener">Open job on Upwork ↗</a></div>
      @endif

      @if(!empty($job->question_answers))
        <div class="qa">
          <h2>Screening answers</h2>
          @foreach($job->question_answers as $a)
            @php
              $q = collect($job->screening_questions)->firstWhere('position', $a['position'] ?? -1);
            @endphp
            <div class="q">❓ {{ $q['question'] ?? 'Question' }}</div>
            <div class="letter" style="margin-top:4px" id="qa-{{ $job->id }}-{{ $a['position'] ?? 0 }}">{{ $a['answer'] ?? '' }}</div>
            <button class="btn ghost sm" style="margin-top:6px" onclick="copyVal('qa-{{ $job->id }}-{{ $a['position'] ?? 0 }}', this)">Copy answer</button>
          @endforeach
        </div>
      @endif
    </div>
  </details>
@empty
  <div class="panel" style="text-align:center;color:var(--muted);padding:44px">
    No jobs yet. Once your webhook URL is set in UpHunt (see <a href="{{ route('settings') }}">Settings</a>),
    matching jobs will appear here with ready-to-paste letters.
  </div>
@endforelse

@if($jobs->hasPages())
  <div class="pager">
    {{ $jobs->links('pagination::simple-default') }}
  </div>
@endif
@endsection
