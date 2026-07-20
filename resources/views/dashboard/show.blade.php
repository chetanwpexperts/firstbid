@extends('layout')
@section('title', $job->title . ' — FirstBid')
@section('content')
<p style="margin-bottom:10px"><a href="{{ route('dashboard') }}" style="color:var(--muted);text-decoration:none">← Back to jobs</a></p>
<h1>{{ $job->title }}</h1>
<p style="color:var(--muted);margin-bottom:16px">
  <span class="score">Score {{ $job->uphunt_score ?? '—' }}</span> ·
  {{ $job->budget_display }} · {{ $job->client_country ?? 'Unknown' }} ·
  ⭐ {{ $job->client_score ?? '—' }} ({{ $job->client_hires ?? '—' }} hires)
  @if(!$job->payment_verified) · 🚩 payment not verified @endif
  · <span class="tag {{ $job->status }}">{{ $job->status }}</span>
</p>

@if ($job->job_url)
  <p style="margin-bottom:16px"><a class="btn" href="{{ $job->job_url }}" target="_blank" rel="noopener">Open job on Upwork ↗</a></p>
@endif

@if ($job->cover_letter)
<div class="panel">
  <h2>Cover letter <button class="copybtn" onclick="copyText('letter', this)">Copy</button>
  @if($job->bid_suggestion) <span class="help" style="display:inline">· {{ $job->bid_suggestion }}</span>@endif
  </h2>
  <pre class="letter" id="letter">{{ $job->cover_letter }}</pre>
</div>
@endif

@if (!empty($job->question_answers))
<div class="panel">
  <h2>Screening answers</h2>
  @foreach ($job->question_answers as $i => $qa)
    @php $q = collect($job->screening_questions)->firstWhere('position', $qa['position'] ?? -1); @endphp
    <p style="font-weight:600;margin:10px 0 4px">❓ {{ $q['question'] ?? 'Question' }}
      <button class="copybtn" onclick="copyText('qa{{ $i }}', this)">Copy</button></p>
    <pre class="letter" id="qa{{ $i }}" style="font-size:13.5px">{{ $qa['answer'] ?? '' }}</pre>
  @endforeach
</div>
@endif

<div class="panel">
  <h2>Job description</h2>
  <pre class="letter" style="font-size:13.5px;color:var(--muted)">{{ $job->description }}</pre>
</div>
@endsection
