@extends('layout')

@section('title', 'Admin Portal — Feedback & Reviews')

@section('content')
<div class="glass-panel">
  <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 24px;">
    <div>
      <h1 style="font-size: 24px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">User & Competitor Feedback</h1>
      <p style="color: var(--text-muted); font-size: 14px;">Review submitted ratings, feature requests, and platform reviews.</p>
    </div>
  </div>

  <div style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
      <thead>
        <tr style="border-bottom: 2px solid var(--border); font-family: var(--font-mono); color: var(--text-muted); font-size: 11.5px; text-transform: uppercase;">
          <th style="padding: 12px 10px;">User</th>
          <th style="padding: 12px 10px;">Rating</th>
          <th style="padding: 12px 10px;">Category</th>
          <th style="padding: 12px 10px;">Feedback Message</th>
          <th style="padding: 12px 10px;">Date</th>
          <th style="padding: 12px 10px; text-align: right;">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($feedbacks as $fb)
        <tr style="border-bottom: 1px solid var(--border);">
          <td style="padding: 14px 10px;">
            <div style="font-weight: 700; color: var(--text-dark);">{{ $fb->user?->name ?? 'Anonymous / Guest' }}</div>
            <div style="font-size: 12.5px; color: var(--text-muted);">{{ $fb->user?->email ?? 'N/A' }}</div>
          </td>

          <td style="padding: 14px 10px; font-size: 14px;">
            @if(($fb->rating ?? 0) > 0)
              @for($i = 1; $i <= 5; $i++)
                <span style="color: {{ $i <= $fb->rating ? '#f59e0b' : '#cbd5e1' }}; font-size: 15px;">★</span>
              @endfor
            @else
              <span style="color: var(--text-muted); font-size: 12px; font-family: var(--font-mono);">📌 Waitlist Entry</span>
            @endif
          </td>

          <td style="padding: 14px 10px;">
            <span class="badge badge-notified" style="font-size: 11px;">
              {{ str_replace('_', ' ', $fb->category) }}
            </span>
          </td>

          <td style="padding: 14px 10px; max-width: 380px;">
            <div style="white-space: pre-wrap; font-size: 13.5px; color: var(--text-dark); line-height: 1.5;">{{ $fb->message }}</div>
          </td>

          <td style="padding: 14px 10px; font-family: var(--font-mono); font-size: 12.5px; color: var(--text-muted);">
            {{ $fb->created_at->format('M d, Y H:i') }}
          </td>

          <td style="padding: 14px 10px; text-align: right;">
            <form method="POST" action="{{ route('admin.feedback.delete', $fb->id) }}" onsubmit="return confirm('Delete this feedback entry?');">
              @csrf
              @method('DELETE')
              <button class="btn btn-danger btn-sm" type="submit">Delete</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="padding: 30px; text-align: center; color: var(--text-muted);">No feedback records found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div style="margin-top: 20px;">
    {{ $feedbacks->links() }}
  </div>
</div>
@endsection
