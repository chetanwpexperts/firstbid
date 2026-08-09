@extends('layout')

@section('title', 'FirstBidIn — Upwork AI Proposal Assistant | Extension')

@section('content')
@php
  $webstoreUrl = config('services.chrome_webstore.url') ?: env('CHROME_WEBSTORE_URL');
@endphp

<!-- Chrome Web Store Style Hero Header -->
<div class="glass-panel" style="padding: 32px; background: #ffffff; border-radius: 16px; border-color: var(--border); box-shadow: 0 8px 30px rgba(0,0,0,0.04); margin-bottom: 32px;">
  <div style="display: flex; gap: 24px; align-items: flex-start; flex-wrap: wrap;">
    
    <!-- App Extension Logo Icon -->
    <div style="width: 72px; height: 72px; background: linear-gradient(135deg, #14a800 0%, #0e7a00 100%); border-radius: 18px; display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 36px; box-shadow: 0 8px 24px rgba(20, 168, 0, 0.3); flex-shrink: 0;">
      ⚡
    </div>

    <!-- Details Column -->
    <div style="flex: 1; min-width: 280px;">
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;">
        <h1 style="font-size: 28px; font-weight: 800; color: var(--text-dark); margin: 0; letter-spacing: -0.02em;">
          FirstBidIn — Upwork AI Proposal Assistant
        </h1>
        <span style="font-size: 11px; font-family: var(--font-mono); font-weight: 800; background: var(--upwork-tint); color: var(--upwork-tint-text); border: 1px solid var(--upwork-tint-border); padding: 3px 8px; border-radius: 6px; text-transform: uppercase;">
          Verified · Manifest V3
        </span>
      </div>

      <p style="font-size: 15px; color: var(--text-muted); margin-bottom: 16px; line-height: 1.5;">
        Draft tailored proposals, generate 3 opener hooks, and auto-fill cover letters and screening question answers directly inside Upwork job pages in 1 click.
      </p>

      <!-- REAL Store Stats & Rating from Database -->
      <div style="display: flex; align-items: center; gap: 20px; font-size: 13.5px; color: var(--text-muted); flex-wrap: wrap; margin-bottom: 20px; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); padding: 10px 0;">
        <div style="display: flex; align-items: center; gap: 6px; font-weight: 700;">
          @if(($reviewsCount ?? 0) > 0)
            <span style="color: #d97706;">⭐ {{ $avgRating }} / 5.0</span>
            <span style="color: var(--text-muted); font-weight: 500;">({{ $reviewsCount }} {{ Str::plural('user review', $reviewsCount) }})</span>
          @else
            <span style="color: var(--text-muted); font-weight: 600;">⭐ No ratings yet — be the first user to rate after trying!</span>
          @endif
        </div>

        <div>🛡️ <span style="font-weight: 600; color: var(--upwork-tint-text);">100% Upwork ToS Safe</span></div>
        <div>🔐 <span style="font-weight: 600; color: var(--text-dark);">FirstBid Plan Required</span></div>
      </div>

      <!-- Store Action Buttons (THEME MATCHED EMERALD GREEN) -->
      <div style="display: flex; gap: 14px; align-items: center; flex-wrap: wrap;">
        @guest
          <a href="{{ route('register') }}" class="btn" style="background: var(--upwork-green); padding: 12px 28px; font-size: 15px; font-weight: 700; border-radius: 24px; box-shadow: 0 4px 14px rgba(20, 168, 0, 0.35); text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
            🔒 Log In or Start Trial to Install Extension
          </a>
        @else
          @if(auth()->user()->is_approved && auth()->user()->canGenerate())
            @if($webstoreUrl)
              <a href="{{ $webstoreUrl }}" target="_blank" class="btn" style="background: var(--upwork-green); padding: 12px 28px; font-size: 15px; font-weight: 700; border-radius: 24px; box-shadow: 0 4px 14px rgba(20, 168, 0, 0.35); display: inline-flex; align-items: center; gap: 10px; text-decoration: none;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 4C14.77 4 17.18 5.4 18.6 7.5H12V4ZM4.4 9C5.4 6.6 7.5 4.9 10.1 4.3L13.6 10.3L4.4 9ZM12 20C9.23 20 6.82 18.6 5.4 16.5H12V20ZM19.6 15C18.6 17.4 16.5 19.1 13.9 19.7L10.4 13.7L19.6 15Z" fill="#FFFFFF"/>
                </svg>
                Add to Chrome
              </a>
            @else
              <button type="button" class="btn" id="addToChromeBtn" onclick="openInstallModal()" style="background: var(--upwork-green); padding: 12px 28px; font-size: 15px; font-weight: 700; border-radius: 24px; box-shadow: 0 4px 14px rgba(20, 168, 0, 0.35); display: inline-flex; align-items: center; gap: 10px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 4C14.77 4 17.18 5.4 18.6 7.5H12V4ZM4.4 9C5.4 6.6 7.5 4.9 10.1 4.3L13.6 10.3L4.4 9ZM12 20C9.23 20 6.82 18.6 5.4 16.5H12V20ZM19.6 15C18.6 17.4 16.5 19.1 13.9 19.7L10.4 13.7L19.6 15Z" fill="#FFFFFF"/>
                </svg>
                Add to Chrome
              </button>
            @endif

            <a href="{{ route('extension.download') }}" class="btn btn-ghost" style="padding: 11px 20px; font-size: 14px; border-radius: 24px;">
              📥 Download Extension Package (.zip)
            </a>
          @else
            <a href="{{ route('dashboard') }}" class="btn" style="background: var(--upwork-green); padding: 12px 24px; font-size: 14px; border-radius: 24px; text-decoration: none;">
              🔒 Upgrade Plan / Complete Account Approval to Download Extension
            </a>
          @endif
        @endguest
      </div>

    </div>
  </div>
</div>

<!-- Detailed Explanation: What is the Extension & How it Works -->
<div class="glass-panel" style="padding: 32px; background: #ffffff; margin-bottom: 32px;">
  <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
    <span style="font-size: 26px;">💡</span>
    <div>
      <h2 style="font-size: 22px; font-weight: 800; color: var(--text-dark); margin: 0;">
        What is the FirstBidIn Extension & How Does it Work?
      </h2>
      <div style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">
        Everything you need to know about using FirstBidIn directly on Upwork job pages.
      </div>
    </div>
  </div>

  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-top: 24px;">
    
    <!-- Step 1 Card -->
    <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 14px; padding: 22px; position: relative;">
      <div style="font-size: 11px; font-family: var(--font-mono); font-weight: 800; color: var(--upwork-green); text-transform: uppercase; margin-bottom: 8px;">STEP 1 · AUTO INJECTION</div>
      <h3 style="font-size: 17px; font-weight: 800; color: var(--text-dark); margin-bottom: 8px;">Open Any Upwork Job Page</h3>
      <p style="font-size: 13.5px; color: var(--text-muted); line-height: 1.55; margin: 0;">
        Navigate to any job detail page or proposal submission page on Upwork (<code style="font-family: var(--font-mono); background: #e2e8f0; padding: 1px 5px; border-radius: 4px;">upwork.com/jobs/*</code>). The extension automatically injects a sleek <b>⚡ FirstBid Proposal AI</b> floating button at the bottom-right of your screen.
      </p>
    </div>

    <!-- Step 2 Card -->
    <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 14px; padding: 22px; position: relative;">
      <div style="font-size: 11px; font-family: var(--font-mono); font-weight: 800; color: var(--upwork-green); text-transform: uppercase; margin-bottom: 8px;">STEP 2 · 1-CLICK AI ANALYSIS</div>
      <h3 style="font-size: 17px; font-weight: 800; color: var(--text-dark); margin-bottom: 8px;">Click "Generate Proposal"</h3>
      <p style="font-size: 13.5px; color: var(--text-muted); line-height: 1.55; margin: 0;">
        Click the floating AI button. FirstBidIn instantly analyzes the client's job title, description, budget, and screening questions, comparing them against your saved freelance profile and portfolio skills.
      </p>
    </div>

    <!-- Step 3 Card -->
    <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 14px; padding: 22px; position: relative;">
      <div style="font-size: 11px; font-family: var(--font-mono); font-weight: 800; color: var(--upwork-green); text-transform: uppercase; margin-bottom: 8px;">STEP 3 · 1-CLICK AUTO-FILL</div>
      <h3 style="font-size: 17px; font-weight: 800; color: var(--text-dark); margin-bottom: 8px;">Auto-Fill Into Upwork Textarea</h3>
      <p style="font-size: 13.5px; color: var(--text-muted); line-height: 1.55; margin: 0;">
        Select your favorite opener hook, review the generated cover letter and screening Q&A answers, and click <b>🚀 Auto-Fill Cover Letter</b> to paste everything straight into Upwork's text fields without leaving your browser tab!
      </p>
    </div>

  </div>
</div>

<!-- Interactive Extension Rating Form Section for Authenticated Users -->
@auth
<div class="glass-panel" style="padding: 24px 28px; background: #ffffff; margin-bottom: 32px; border-color: var(--upwork-tint-border);">
  <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 16px;">
    <div>
      <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin: 0;">
        ⭐ {{ $userReview ? 'Your Extension Rating & Review' : 'Rate & Review this Extension' }}
      </h2>
      <p style="font-size: 13.5px; color: var(--text-muted); margin-top: 4px;">
        Share your experience after using the extension on Upwork.
      </p>
    </div>
    @if($userReview)
      <span class="badge badge-notified">You rated {{ $userReview->rating }}/5 stars</span>
    @endif
  </div>

  <form method="POST" action="{{ route('extension.review') }}" style="display: flex; flex-direction: column; gap: 14px;">
    @csrf
    <div style="display: flex; align-items: center; gap: 10px;">
      <label style="font-weight: 700; font-size: 14px;">Rating:</label>
      <div style="display: flex; gap: 8px;">
        @for($i = 5; $i >= 1; $i--)
          <label style="cursor: pointer; font-size: 20px; display: flex; align-items: center; gap: 4px;">
            <input type="radio" name="rating" value="{{ $i }}" {{ ($userReview?->rating ?? 5) == $i ? 'checked' : '' }} required>
            <span>{{ $i }} ★</span>
          </label>
        @endfor
      </div>
    </div>

    <div>
      <textarea name="review_text" rows="2" placeholder="Write your review or feedback (optional)..." style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 13.5px; font-family: inherit;">{{ $userReview?->review_text }}</textarea>
    </div>

    <div style="text-align: right;">
      <button type="submit" class="btn btn-sm" style="background: var(--upwork-green); padding: 8px 20px; font-size: 13.5px;">
        Submit Rating & Review
      </button>
    </div>
  </form>
</div>
@endauth

<!-- Community User Reviews Section -->
@if(($recentReviews ?? collect())->count() > 0)
<div class="glass-panel" style="padding: 28px; background: #ffffff; margin-bottom: 32px;">
  <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 16px;">
    💬 User Ratings & Feedback ({{ $reviewsCount }})
  </h2>

  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
    @foreach($recentReviews as $rev)
      <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px; padding: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
          <div style="font-weight: 700; font-size: 14px; color: var(--text-dark);">
            {{ $rev->user?->name ?? 'Freelancer' }}
          </div>
          <div style="color: #d97706; font-size: 13px; font-weight: 700;">
            {{ str_repeat('★', $rev->rating) }}{{ str_repeat('☆', 5 - $rev->rating) }}
          </div>
        </div>
        @if($rev->review_text)
          <p style="font-size: 13px; color: var(--text-main); margin: 0; line-height: 1.5;">
            "{{ $rev->review_text }}"
          </p>
        @else
          <p style="font-size: 12px; color: var(--text-muted); font-style: italic; margin: 0;">
            Rated {{ $rev->rating }} out of 5 stars
          </p>
        @endif
        <div style="font-size: 11px; color: var(--text-muted); margin-top: 8px; font-family: var(--font-mono);">
          {{ $rev->created_at->diffForHumans() }}
        </div>
      </div>
    @endforeach
  </div>
</div>
@endif

<!-- Extension Feature Preview Cards -->
<div class="glass-panel" style="padding: 32px; background: #ffffff;">
  <h2 style="font-size: 20px; font-weight: 800; color: var(--text-dark); margin-bottom: 6px;">
    Overview & Live Features
  </h2>
  <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 24px;">
    FirstBidIn injects a smart proposal workspace overlay inside Upwork, allowing you to win more jobs in 2 minutes.
  </p>

  <!-- Interactive Upwork Overlay Demo Box -->
  <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 14px; padding: 24px; margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
      <div style="font-size: 12px; font-family: var(--font-mono); color: var(--text-muted); font-weight: 700; text-transform: uppercase;">
        🌐 UPWORK JOB APPLICATION PAGE INTERFACE
      </div>
      <span class="badge badge-notified">1-Click Auto-Fill Ready</span>
    </div>

    <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 10px; padding: 20px; margin-bottom: 16px;">
      <h3 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 10px;">Senior Laravel Developer for SaaS System</h3>
      <p style="font-size: 13.5px; color: var(--text-main); line-height: 1.55;">
        We need an experienced Laravel developer to optimize database queries, build custom API webhooks, and fix performance issues. Budget: $1,500 fixed.
      </p>
    </div>

    <!-- Injected FirstBid Assistant Banner -->
    <div style="background: linear-gradient(135deg, #ecfdf0 0%, #f4fbf5 100%); border: 2px solid var(--upwork-tint-border); border-radius: 12px; padding: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
      <div style="display: flex; align-items: center; gap: 12px;">
        <div style="width: 36px; height: 36px; background: var(--upwork-green); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 18px; font-weight: 800;">⚡</div>
        <div>
          <div style="font-weight: 800; font-size: 15px; color: var(--upwork-deep);">FirstBidIn Assistant Widget</div>
          <div style="font-size: 13px; color: var(--upwork-tint-text);">3 Opener Hooks · Scope Breakdown (~16 hrs) · Screening Q&A</div>
        </div>
      </div>

      <button type="button" class="btn" onclick="alert('⚡ FirstBidIn extension auto-fills proposal text instantly into Upwork cover letter textarea!')" style="background: var(--upwork-green); padding: 9px 18px; font-size: 13.5px; font-weight: 700;">
        🪄 Auto-Fill Proposal Into Upwork
      </button>
    </div>
  </div>

  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">
    <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 12px; padding: 20px;">
      <div style="font-size: 24px; margin-bottom: 10px;">🎯</div>
      <h3 style="font-weight: 800; font-size: 16px; color: var(--text-dark); margin-bottom: 6px;">3 "First 2 Lines" Opener Hooks</h3>
      <p style="font-size: 13.5px; color: var(--text-muted); line-height: 1.5;">Pick between Problem-Direct, Results & Metrics, or Fast Execution opener hooks designed for Upwork's client preview window.</p>
    </div>

    <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 12px; padding: 20px;">
      <div style="font-size: 24px; margin-bottom: 10px;">📊</div>
      <h3 style="font-weight: 800; font-size: 16px; color: var(--text-dark); margin-bottom: 6px;">Subtask Effort Breakdown</h3>
      <p style="font-size: 13.5px; color: var(--text-muted); line-height: 1.5;">Provides mathematical hour breakdowns for deliverables and recommended deposit milestone phases.</p>
    </div>

    <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 12px; padding: 20px;">
      <div style="font-size: 24px; margin-bottom: 10px;">🛡️</div>
      <h3 style="font-weight: 800; font-size: 16px; color: var(--text-dark); margin-bottom: 6px;">Human-in-the-Loop Safety</h3>
      <p style="font-size: 13.5px; color: var(--text-muted); line-height: 1.5;">FirstBid drafts proposal text for your review; you retain 100% control to review and submit. Zero risk of automated bot bidding.</p>
    </div>
  </div>
</div>

@auth
<!-- Add to Chrome Quick Installation Guide Modal (AUTH USERS ONLY) -->
<div id="installModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(6px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
  <div style="background: #ffffff; border-radius: 20px; max-width: 520px; width: 100%; padding: 28px; box-shadow: 0 20px 50px rgba(0,0,0,0.25); text-align: left; position: relative;">
    <button type="button" onclick="closeInstallModal()" style="position: absolute; right: 18px; top: 18px; background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text-muted);">✕</button>

    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 16px;">
      <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #14a800 0%, #0e7a00 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; box-shadow: 0 4px 14px rgba(20, 168, 0, 0.3); flex-shrink: 0;">
        ⚡
      </div>
      <div>
        <h3 style="font-size: 19px; font-weight: 800; color: var(--text-dark); margin: 0;">
          Add FirstBidIn to Browser
        </h3>
        <div style="font-size: 12.5px; color: var(--upwork-tint-text); font-weight: 700;">Zero Token Setup · Auto Account Sync</div>
      </div>
    </div>

    <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 12px; padding: 16px; margin-bottom: 20px; font-size: 13.5px; color: var(--text-main); line-height: 1.6;">
      <div style="font-weight: 700; color: var(--text-dark); margin-bottom: 8px;">Quick 2-Step Browser Install:</div>
      <ol style="margin-left: 18px; display: flex; flex-direction: column; gap: 8px;">
        <li>Click <b>Download Package</b> below to get the extension folder.</li>
        <li>Open <code style="font-family: var(--font-mono); background: #e2e8f0; padding: 2px 6px; border-radius: 4px; color: var(--text-dark);">chrome://extensions</code> → Enable <b>Developer mode</b> → Click <b>Load unpacked</b> and select the unzipped folder.</li>
      </ol>
    </div>

    <div style="display: flex; gap: 10px; justify-content: flex-end;">
      <button type="button" class="btn btn-ghost" onclick="closeInstallModal()" style="padding: 10px 18px; font-size: 13.5px;">Close</button>
      <a href="{{ route('extension.download') }}" class="btn" style="background: var(--upwork-green); padding: 10px 22px; font-size: 14px; font-weight: 700; border-radius: 10px; text-decoration: none; display: flex; align-items: center; gap: 8px;">
        📥 Download Package & Install
      </a>
    </div>
  </div>
</div>

<script>
function openInstallModal() {
  document.getElementById('installModal').style.display = 'flex';
}
function closeInstallModal() {
  document.getElementById('installModal').style.display = 'none';
}
</script>
@endauth
@endsection
