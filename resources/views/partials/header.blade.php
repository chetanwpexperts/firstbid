<header class="app-header">
  <div class="header-container">
    <a class="brand-logo" href="{{ auth()->check() ? route('dashboard') : '/' }}">
      First<span class="accent">Bid</span>
      <span class="ai-badge">AI 2.0</span>
    </a>

    @auth
    <nav class="nav-links">
      <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Jobs Inbox</a>
      <a class="nav-link {{ request()->routeIs('extension') ? 'active' : '' }}" href="{{ route('extension') }}">Extension 🧩</a>
      <a class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}" href="{{ route('settings') }}">Settings</a>
      @if(auth()->user()?->is_admin)
      <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">Users</a>
      <a class="nav-link {{ request()->routeIs('admin.feedback') ? 'active' : '' }}" href="{{ route('admin.feedback') }}">Feedback</a>
      @endif

      <button type="button" class="btn btn-ghost btn-sm" onclick="openFeedbackModal()" style="font-size: 13px; padding: 5px 11px;">💬 Feedback</button>

      <div class="notif-bell" id="notifBell" style="position: relative;">
        <button type="button" class="notif-toggle" onclick="toggleNotifDropdown()" aria-label="Notifications" style="background: none; border: none; font-size: 17px; cursor: pointer; color: var(--text-muted); position: relative; padding: 4px 8px;">
          🔔
          @if(($unseenJobsCount ?? 0) > 0)
            <span class="notif-badge" id="notifBadge" style="position: absolute; top: -2px; right: 0; background: var(--upwork-green); color: #ffffff; font-size: 10px; font-weight: 800; font-family: var(--font-mono); border-radius: 10px; padding: 1px 6px;">{{ $unseenJobsCount > 99 ? '99+' : $unseenJobsCount }}</span>
          @endif
        </button>
        <div class="notif-dropdown" id="notifDropdown" style="display: none; position: absolute; right: 0; top: 38px; width: 320px; background: #ffffff; border: 1px solid var(--border); border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); z-index: 200; padding: 14px;">
          <div style="font-size: 11.5px; font-family: var(--font-mono); text-transform: uppercase; color: var(--text-muted); margin-bottom: 10px; font-weight: 700;">Unread Job Alerts</div>
          @forelse($unseenJobs ?? [] as $job)
            <a href="{{ route('jobs.show', $job->id) }}" style="display: block; padding: 8px 0; border-bottom: 1px solid var(--border); text-decoration: none; color: var(--text-dark);">
              <div style="font-weight: 600; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $job->title }}</div>
              <div style="font-size: 11.5px; color: var(--text-muted); font-family: var(--font-mono);">Score {{ $job->uphunt_score ?? '—' }} · {{ $job->budget_display }}</div>
            </a>
          @empty
            <div style="font-size: 13px; color: var(--text-muted); padding: 12px 0; text-align: center;">No new unread job alerts.</div>
          @endforelse
          <a href="{{ route('dashboard') }}" style="display: block; text-align: center; margin-top: 12px; font-size: 12.5px; font-weight: 600; color: var(--upwork-green);">View all jobs in inbox ↗</a>
        </div>
      </div>

      <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
        @csrf
        <button class="btn btn-ghost btn-sm" type="submit">Log out</button>
      </form>
    </nav>
    @else
    <nav class="nav-links">
      <a class="nav-link {{ request()->routeIs('extension') ? 'active' : '' }}" href="{{ route('extension') }}">Extension 🧩</a>
      <a class="nav-link" href="{{ route('login') }}">Log in</a>
      <a class="btn btn-sm" href="{{ route('register') }}">Start Free Trial</a>
    </nav>
    @endauth
  </div>
</header>

<script>
function toggleNotifDropdown() {
  const dd = document.getElementById('notifDropdown');
  if (!dd) return;
  const isHidden = dd.style.display === 'none';
  dd.style.display = isHidden ? 'block' : 'none';
  if (isHidden) {
    fetch('{{ route("notifications.seen") }}', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    }).then(() => {
      const b = document.getElementById('notifBadge');
      if (b) b.style.display = 'none';
    });
  }
}
document.addEventListener('click', function(e) {
  const bell = document.getElementById('notifBell');
  const dd = document.getElementById('notifDropdown');
  if (bell && dd && !bell.contains(e.target)) {
    dd.style.display = 'none';
  }
});
</script>
