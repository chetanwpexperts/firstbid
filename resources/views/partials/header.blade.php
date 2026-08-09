<header class="app-header">
  <div class="header-container">
    <!-- Brand Logo -->
    <a class="brand-logo" href="{{ auth()->check() ? route('dashboard') : '/' }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
      <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #14a800 0%, #0e7a00 100%); border-radius: 9px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(20, 168, 0, 0.25); flex-shrink: 0;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M13 2L3 14H12L11 22L21 10H12L13 2Z" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="1.5" stroke-linejoin="round"/>
        </svg>
      </div>

      <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0; white-space: nowrap;">
        <span style="font-size: 21px; font-weight: 800; letter-spacing: -0.03em; color: var(--text-dark); line-height: 1;">
          First<span style="color: var(--upwork-green);">Bid</span><span style="color: var(--text-dark); font-weight: 800;">.in</span>
        </span>
        <span class="ai-badge" style="font-size: 10px; font-family: var(--font-mono); background: var(--upwork-tint); color: var(--upwork-tint-text); border: 1px solid var(--upwork-tint-border); padding: 2px 6px; border-radius: 4px; font-weight: 800; text-transform: uppercase; white-space: nowrap;">AI 2.0</span>
      </div>
    </a>

    @auth
    <!-- Primary Navigation Links -->
    <nav class="nav-links">
      <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Jobs Inbox</a>
      <a class="nav-link {{ request()->routeIs('extension') ? 'active' : '' }}" href="{{ route('extension') }}">Extension 🧩</a>
      <a class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}" href="{{ route('blog.index') }}">Blog 📚</a>

      @if(auth()->user()?->is_admin)
      <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">Users</a>
      <a class="nav-link {{ request()->routeIs('admin.feedback') ? 'active' : '' }}" href="{{ route('admin.feedback') }}">Feedback</a>
      <a class="nav-link {{ request()->routeIs('admin.blogs') ? 'active' : '' }}" href="{{ route('admin.blogs') }}">Blogs Admin</a>
      @endif
    </nav>

    <!-- Right Header Action Bar (Notifications + User Dropdown) -->
    <div style="display: flex; align-items: center; gap: 14px;">
      <!-- Notifications Bell -->
      <div class="notif-bell" id="notifBell" style="position: relative;">
        <button type="button" class="notif-toggle" onclick="toggleNotifDropdown()" aria-label="Notifications" style="background: none; border: none; font-size: 17px; cursor: pointer; color: var(--text-muted); position: relative; padding: 6px 8px; display: flex; align-items: center; justify-content: center;">
          🔔
          @if(($unseenJobsCount ?? 0) > 0)
            <span class="notif-badge" id="notifBadge" style="position: absolute; top: 0; right: 0; background: var(--upwork-green); color: #ffffff; font-size: 10px; font-weight: 800; font-family: var(--font-mono); border-radius: 10px; padding: 1px 6px;">{{ $unseenJobsCount > 99 ? '99+' : $unseenJobsCount }}</span>
          @endif
        </button>
        <div class="notif-dropdown" id="notifDropdown" style="display: none; position: absolute; right: 0; top: 42px; width: 320px; background: #ffffff; border: 1px solid var(--border); border-radius: 12px; box-shadow: 0 12px 36px rgba(0,0,0,0.12); z-index: 200; padding: 14px;">
          <div style="font-size: 11px; font-family: var(--font-mono); text-transform: uppercase; color: var(--text-muted); margin-bottom: 10px; font-weight: 700;">Unread Job Alerts</div>
          @forelse($unseenJobs ?? [] as $job)
            <a href="{{ route('jobs.show', $job) }}" style="display: block; padding: 8px 0; border-bottom: 1px solid var(--border); text-decoration: none; color: var(--text-dark);">
              <div style="font-weight: 600; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $job->title }}</div>
              <div style="font-size: 11.5px; color: var(--text-muted); font-family: var(--font-mono);">Score {{ $job->uphunt_score ?? '—' }} · {{ $job->budget_display }}</div>
            </a>
          @empty
            <div style="font-size: 13px; color: var(--text-muted); padding: 12px 0; text-align: center;">No new unread job alerts.</div>
          @endforelse
          <a href="{{ route('dashboard') }}" style="display: block; text-align: center; margin-top: 12px; font-size: 12.5px; font-weight: 600; color: var(--upwork-green);">View all jobs in inbox ↗</a>
        </div>
      </div>

      <!-- User Profile Dropdown -->
      <div class="user-menu-dropdown" id="userMenuDropdown" style="position: relative;">
        <button type="button" onclick="toggleUserDropdown()" style="background: #ffffff; border: 1px solid var(--border); border-radius: 20px; padding: 4px 12px 4px 6px; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
          <!-- User Avatar Badge -->
          <div style="width: 28px; height: 28px; background: var(--upwork-green); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px; font-family: var(--font-mono);">
            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
          </div>
          <span style="font-size: 13.5px; font-weight: 700; color: var(--text-dark); max-width: 120px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            {{ auth()->user()->name ?? 'Account' }}
          </span>
          <span style="font-size: 10px; color: var(--text-muted);">▼</span>
        </button>

        <div class="user-menu-list" id="userMenuList" style="display: none; position: absolute; right: 0; top: 44px; width: 200px; background: #ffffff; border: 1px solid var(--border); border-radius: 12px; box-shadow: 0 12px 36px rgba(0,0,0,0.12); z-index: 200; padding: 8px 0;">
          <div style="padding: 10px 16px; border-bottom: 1px solid var(--border); margin-bottom: 4px;">
            <div style="font-weight: 800; font-size: 13.5px; color: var(--text-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ auth()->user()->name }}</div>
            <div style="font-size: 11.5px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ auth()->user()->email }}</div>
          </div>

          <a href="{{ route('settings') }}" style="display: flex; align-items: center; gap: 10px; padding: 9px 16px; color: var(--text-dark); text-decoration: none; font-size: 13.5px; font-weight: 600; transition: background 0.15s ease;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
            ⚙️ Settings Profile
          </a>

          <button type="button" onclick="openTourModal(); toggleUserDropdown();" style="width: 100%; text-align: left; background: none; border: none; display: flex; align-items: center; gap: 10px; padding: 9px 16px; color: var(--text-dark); font-size: 13.5px; font-weight: 600; cursor: pointer; transition: background 0.15s ease;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
            💡 Interactive Tour
          </button>

          <button type="button" onclick="openFeedbackModal(); toggleUserDropdown();" style="width: 100%; text-align: left; background: none; border: none; display: flex; align-items: center; gap: 10px; padding: 9px 16px; color: var(--text-dark); font-size: 13.5px; font-weight: 600; cursor: pointer; transition: background 0.15s ease;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
            💬 Send Feedback
          </button>

          <div style="border-top: 1px solid var(--border); margin: 4px 0;"></div>

          <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
            @csrf
            <button type="submit" style="width: 100%; text-align: left; background: none; border: none; display: flex; align-items: center; gap: 10px; padding: 9px 16px; color: #dc2626; font-size: 13.5px; font-weight: 700; cursor: pointer; transition: background 0.15s ease;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">
              🚪 Log Out
            </button>
          </form>
        </div>
      </div>
    </div>
    @else
    <!-- Guest Navigation Links -->
    <nav class="nav-links">
      <a class="nav-link {{ request()->routeIs('extension') ? 'active' : '' }}" href="{{ route('extension') }}">Extension 🧩</a>
      <a class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}" href="{{ route('blog.index') }}">Blog 📚</a>
      <a class="nav-link" href="{{ route('login') }}">Log in</a>
      <a class="btn btn-sm" href="{{ route('register') }}">Start Free Trial</a>
    </nav>
    @endauth
  </div>
</header>

<script>
function toggleNotifDropdown() {
  const dd = document.getElementById('notifDropdown');
  const userDd = document.getElementById('userMenuList');
  if (userDd) userDd.style.display = 'none';
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

function toggleUserDropdown() {
  const dd = document.getElementById('userMenuList');
  const notifDd = document.getElementById('notifDropdown');
  if (notifDd) notifDd.style.display = 'none';
  if (!dd) return;
  dd.style.display = dd.style.display === 'none' ? 'block' : 'none';
}

document.addEventListener('click', function(e) {
  const bell = document.getElementById('notifBell');
  const notifDd = document.getElementById('notifDropdown');
  const userBtn = document.getElementById('userMenuDropdown');
  const userDd = document.getElementById('userMenuList');

  if (bell && notifDd && !bell.contains(e.target)) {
    notifDd.style.display = 'none';
  }
  if (userBtn && userDd && !userBtn.contains(e.target)) {
    userDd.style.display = 'none';
  }
});
</script>
