<footer class="app-footer">
  <div class="footer-gradient-bar"></div>
  <div class="footer-container">
    <div class="footer-top-row">
      <!-- Brand Column -->
      <div class="footer-brand">
        <a href="{{ auth()->check() ? route('dashboard') : '/' }}" class="footer-logo">
          <div class="footer-icon-badge">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M13 2L3 14H12L11 22L21 10H12L13 2Z" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="1.5" stroke-linejoin="round"/>
            </svg>
          </div>
          <div class="footer-logo-text">
            First<span class="accent">Bid</span>.in
          </div>
          <span class="footer-ai-badge">AI 2.0</span>
        </a>
        <p class="footer-tagline">Account-Safe Automated Job Proposal & Budget Estimator AI</p>
      </div>

      <!-- Quick Nav Links -->
      <div class="footer-links">
        @auth
          <a href="{{ route('dashboard') }}" class="footer-link">Jobs Inbox</a>
          <a href="{{ route('extension') }}" class="footer-link">Chrome Extension</a>
          <a href="{{ route('settings') }}" class="footer-link">Settings Profile</a>
          <button type="button" class="footer-link-btn" onclick="openFeedbackModal()">Send Feedback</button>
        @else
          <a href="{{ route('login') }}" class="footer-link">Log In</a>
          <a href="{{ route('register') }}" class="footer-link">Start Free Trial</a>
          <a href="{{ route('extension') }}" class="footer-link">Chrome Extension</a>
        @endif
      </div>

      <!-- Live Status Pill -->
      <div class="footer-status-box">
        <div class="status-pulse-dot"></div>
        <span class="status-text">Account-Safe Feed Live</span>
      </div>
    </div>

    <!-- Divider -->
    <div class="footer-divider"></div>

    <!-- Bottom Meta Row -->
    <div class="footer-bottom-row">
      <div class="footer-copy">
        © {{ date('Y') }} <strong>FirstBid.in AI Inc.</strong> All rights reserved.
      </div>
      <div class="footer-compliance">
        🛡️ Zero Upwork API Tokens • Local RSS & Webhook Native
      </div>
    </div>
  </div>
</footer>
