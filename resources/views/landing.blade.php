@extends('layout')

@section('title', 'FirstBidIn — Apply to Upwork Jobs in 2 Minutes with AI Scope & Budget Estimator')

@section('content')
<!-- Promotional Launch Banner -->
<div style="background: linear-gradient(90deg, #14a800 0%, #0e7a00 100%); color: #ffffff; text-align: center; padding: 10px 16px; border-radius: 10px; font-weight: 700; font-size: 14px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(20, 168, 0, 0.25); display: flex; align-items: center; justify-content: center; gap: 8px;">
  <span>🎉 <b>LIMITED FOUNDER LAUNCH:</b> Get 30 Days Full Access for <b>$0 / ₹0</b> (Regular <s style="opacity: 0.8;">$29/mo</s>) — No Credit Card Required!</span>
</div>

<div style="text-align: center; padding: 20px 0 45px;">
  <div style="display: inline-flex; align-items: center; gap: 8px; background: var(--upwork-tint); border: 1px solid var(--upwork-tint-border); color: var(--upwork-tint-text); font-family: var(--font-mono); font-size: 12px; padding: 6px 16px; border-radius: 20px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 24px;">
    ⚡ Account-Safe · No Auto-Apply · AI Scope & Budget Estimator
  </div>

  <h1 style="font-size: clamp(32px, 5vw, 54px); font-weight: 800; line-height: 1.15; letter-spacing: -0.03em; max-width: 880px; margin: 0 auto 20px; color: var(--upwork-deep);">
    Win Upwork Jobs in <span style="color: var(--upwork-green);">2 Minutes</span> — Powered by Scope & Budget AI
  </h1>

  <p style="font-size: 18px; color: var(--text-muted); max-width: 680px; margin: 0 auto 32px; line-height: 1.6;">
    FirstBidIn turns real-time job alerts into <b>mathematically estimated budgets</b>, task-by-task effort timelines, and ready-to-submit proposals written in your voice — straight to your inbox & Telegram.
  </p>

  <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
    <a class="btn" href="{{ route('register') }}" style="padding: 14px 32px; font-size: 16px;">Claim Free $0 Founder Pass ↗</a>
    <a class="btn btn-ghost" href="#features" style="padding: 14px 28px; font-size: 16px;">Explore Features</a>
  </div>
  <p style="font-size: 13px; color: var(--text-dim); margin-top: 14px;">No credit card required · Works with UpHunt, RSS & Webhooks</p>
</div>

<!-- Live AI Demo Card -->
<div class="glass-panel" style="max-width: 820px; margin: 0 auto 60px; border-color: var(--upwork-tint-border); background: #ffffff;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--border); padding-bottom: 14px; flex-wrap: wrap; gap: 10px;">
    <div style="display: flex; align-items: center; gap: 10px;">
      <span class="badge badge-notified" style="font-size: 12px;">SCORE 9 · High Match</span>
      <span style="font-weight: 800; color: var(--text-dark); font-size: 16px;">Laravel Application Speed Update</span>
    </div>
    <span style="font-family: var(--font-mono); color: var(--upwork-green); font-weight: 800; font-size: 14px;">$40.0–60.0/hr</span>
  </div>

  <div class="ai-estimator-box" style="margin-bottom: 18px;">
    <div class="ai-estimator-header">
      <div>💰 Recommended Bid: <span style="color: var(--upwork-dark); font-family: var(--font-mono); font-weight: 800;">$45/hr (estimated 14–18 hours)</span></div>
      <div>⏱️ Estimated Duration: <span style="color: var(--upwork-dark); font-family: var(--font-mono); font-weight: 800;">5–7 Days</span></div>
    </div>
    <div class="ai-estimator-strategy">
      💡 <b>AI Scope & Pricing Strategy:</b> Work breaks into 4 technical subtasks totaling 16–18 hours. At $45/hr that's roughly $720–$810 full engagement with staging cycles without rushing go-live.
    </div>
    <div class="ai-task-grid">
      <div class="ai-task-card"><span>Performance audit & profiling</span><span class="hrs">~3h</span></div>
      <div class="ai-task-card"><span>Query & Eloquent N+1 optimization</span><span class="hrs">~5h</span></div>
      <div class="ai-task-card"><span>Redis/Route caching layer</span><span class="hrs">~4h</span></div>
      <div class="ai-task-card"><span>Staging benchmarking & go-live prep</span><span class="hrs">~5h</span></div>
    </div>
  </div>

  <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px; padding: 18px; font-size: 14px; line-height: 1.65; color: var(--text-dark);">
    <div style="font-size: 11.5px; font-family: var(--font-mono); color: var(--upwork-green); font-weight: 800; text-transform: uppercase; margin-bottom: 6px;">AI Generated Proposal Preview:</div>
    "Your Laravel app's speed issues are affecting real users — that needs a methodical fix, not guesswork. I'll profile the bottlenecks first (slow queries, missing indexes, unoptimized Eloquent calls) before touching anything in production..."
  </div>
</div>

<!-- Features Grid -->
<div id="features" style="margin-bottom: 60px;">
  <h2 style="text-align: center; font-size: 28px; font-weight: 800; color: var(--text-dark); margin-bottom: 8px;">Everything You Need to Win Upwork Clients</h2>
  <p style="text-align: center; color: var(--text-muted); margin-bottom: 36px; font-size: 15px;">Designed for professional freelancers who want speed, accuracy, and account safety.</p>

  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
    <div class="glass-panel" style="margin: 0;">
      <div style="font-size: 28px; margin-bottom: 12px;">📊</div>
      <h3 style="font-size: 18px; color: var(--text-dark); font-weight: 700; margin-bottom: 8px;">AI Scope & Budget Calculator</h3>
      <p style="color: var(--text-muted); font-size: 14px;">Calculates subtask hours, delivery duration, and pricing strategies. Detects placeholder $100 client budgets for 10+ day jobs and suggests milestone breakdowns.</p>
    </div>

    <div class="glass-panel" style="margin: 0;">
      <div style="font-size: 28px; margin-bottom: 12px;">💳</div>
      <h3 style="font-size: 18px; color: var(--text-dark); font-weight: 700; margin-bottom: 8px;">Payment Verification Filter</h3>
      <p style="color: var(--text-muted); font-size: 14px;">Filters out unverified client listings automatically. Protect your Connects and letter quota by only focusing on serious, verified clients.</p>
    </div>

    <div class="glass-panel" style="margin: 0;">
      <div style="font-size: 28px; margin-bottom: 12px;">⚡</div>
      <h3 style="font-size: 18px; color: var(--text-dark); font-weight: 700; margin-bottom: 8px;">Auto vs. On-Demand Proposals</h3>
      <p style="color: var(--text-muted); font-size: 14px;">Full control over letter quota. Choose to auto-write proposals on alert arrival or capture jobs and generate proposals manually with 1 click.</p>
    </div>

    <div class="glass-panel" style="margin: 0;">
      <div style="font-size: 28px; margin-bottom: 12px;">🛡️</div>
      <h3 style="font-size: 18px; color: var(--text-dark); font-weight: 700; margin-bottom: 8px;">Account Security & Admin Approval</h3>
      <p style="color: var(--text-muted); font-size: 14px;">Zero risk of Upwork bans. FirstBid never logs into Upwork or auto-submits. New registrations require Admin approval for platform safety.</p>
    </div>

    <div class="glass-panel" style="margin: 0;">
      <div style="font-size: 28px; margin-bottom: 12px;">📱</div>
      <h3 style="font-size: 18px; color: var(--text-dark); font-weight: 700; margin-bottom: 8px;">Telegram & Dashboard Alerts</h3>
      <p style="color: var(--text-muted); font-size: 14px;">Instant alerts sent straight to your phone and dashboard. Open the alert, copy the AI proposal, and submit before competitors even see the job.</p>
    </div>

    <div class="glass-panel" style="margin: 0;">
      <div style="font-size: 28px; margin-bottom: 12px;">📝</div>
      <h3 style="font-size: 18px; color: var(--text-dark); font-weight: 700; margin-bottom: 8px;">Screening Questions Answered</h3>
      <p style="color: var(--text-muted); font-size: 14px;">Required Upwork screening questions are automatically answered based on your real past experience and profile skills.</p>
    </div>
  </div>
</div>

<!-- Promotional Pricing Section -->
<div class="glass-panel" style="max-width: 480px; margin: 0 auto 60px; text-align: center; border-color: var(--upwork-green); border-width: 2px;">
  <div style="font-size: 12px; font-family: var(--font-mono); text-transform: uppercase; color: var(--upwork-green); font-weight: 800; margin-bottom: 6px;">🎉 Promotional Launch Offer</div>
  <div style="font-size: 20px; text-decoration: line-through; color: var(--text-dim); margin-bottom: 2px;">$29 / month</div>
  <div style="font-size: 48px; font-weight: 800; color: var(--upwork-green);">$0 <span style="font-size: 18px; color: var(--text-muted); font-weight: 500;">/ 30 Days</span></div>
  <div style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 20px;">100% Free Founder Pass · No Credit Card Required</div>

  <ul style="text-align: left; list-style: none; margin-bottom: 24px; font-size: 14.5px; color: var(--text-dark);">
    <li style="padding: 8px 0; border-bottom: 1px solid var(--border);">✓ Up to 100 AI Proposals / month</li>
    <li style="padding: 8px 0; border-bottom: 1px solid var(--border);">✓ AI Scope & Budget Estimator</li>
    <li style="padding: 8px 0; border-bottom: 1px solid var(--border);">✓ 3 Opener Hook Options Generator</li>
    <li style="padding: 8px 0; border-bottom: 1px solid var(--border);">✓ Upwork Deposit Milestone Breakdown</li>
    <li style="padding: 8px 0; border-bottom: 1px solid var(--border);">✓ Payment Verification Filtering</li>
    <li style="padding: 8px 0; border-bottom: 1px solid var(--border);">✓ Telegram Alerts & Unread Bell Dropdown</li>
    <li style="padding: 8px 0;">✓ Works with UpHunt, Vibeworker & Webhooks</li>
  </ul>

  <a class="btn" href="{{ route('register') }}" style="width: 100%; padding: 14px; font-size: 16px;">Claim Free $0 Founder Pass ↗</a>
</div>
@endsection
