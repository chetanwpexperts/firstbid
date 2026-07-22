@extends('layout')

@section('title', 'FirstBid AI — Win Upwork Jobs in Minutes with AI Budget Estimator & Custom Proposals')

@section('content')
<div style="text-align: center; padding: 40px 0 50px;">
  <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.25); color: var(--emerald-light); font-family: var(--font-mono); font-size: 12px; padding: 6px 16px; border-radius: 20px; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 24px;">
    ⚡ Account-Safe · No Auto-Apply · AI Budget & Timeline Estimator
  </div>

  <h1 style="font-size: clamp(32px, 5vw, 56px); font-weight: 800; line-height: 1.15; letter-spacing: -0.03em; max-width: 860px; margin: 0 auto 20px; color: #fff;">
    Win Upwork Jobs in <span style="color: var(--emerald); text-shadow: 0 0 20px var(--emerald-glow);">2 Minutes</span> — Powered by Scope & Budget AI
  </h1>

  <p style="font-size: 18px; color: var(--text-muted); max-width: 680px; margin: 0 auto 32px; line-height: 1.6;">
    FirstBid turns real-time job alerts into <b>mathematically estimated budgets</b>, task-by-task effort timelines, and ready-to-submit proposals written in your voice — straight to your inbox & Telegram.
  </p>

  <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
    <a class="btn" href="{{ route('register') }}" style="padding: 14px 32px; font-size: 16px;">Start 30-Day Free Trial ↗</a>
    <a class="btn btn-ghost" href="#features" style="padding: 14px 28px; font-size: 16px;">Explore AI Features</a>
  </div>
  <p style="font-size: 13px; color: var(--text-dim); margin-top: 14px;">No credit card required · Works with UpHunt, RSS & Webhooks</p>
</div>

<!-- Live AI Demo Card -->
<div class="glass-panel" style="max-width: 800px; margin: 0 auto 60px; border-color: rgba(16, 185, 129, 0.3); background: rgba(18, 26, 22, 0.95);">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
    <div style="display: flex; align-items: center; gap: 10px;">
      <span class="badge badge-notified" style="font-size: 12px;">SCORE 9 · High Match</span>
      <span style="font-weight: 700; color: #fff;">Laravel Application Speed Update</span>
    </div>
    <span style="font-family: var(--font-mono); color: var(--emerald-light); font-size: 13px;">$40.0–60.0/hr</span>
  </div>

  <div class="ai-estimator-box" style="margin-bottom: 16px;">
    <div class="ai-estimator-header">
      <div>💰 Recommended Bid: <span style="color: #fff; font-family: var(--font-mono);">$45/hr (estimated 14–18 hours)</span></div>
      <div>⏱️ Estimated Duration: <span style="color: #fff; font-family: var(--font-mono);">5–7 Days</span></div>
    </div>
    <div class="ai-estimator-strategy">
      💡 <b>AI Pricing Strategy:</b> Scope breaks into 4 technical subtasks totaling 16–18 hours. At $45/hr that's roughly $720–$810 full engagement with proper staging validation.
    </div>
    <div class="ai-task-list">
      📋 <b>Task Breakdown:</b>
      <ul>
        <li>Performance audit & Telescope profiling (~3h)</li>
        <li>Query & Eloquent N+1 optimization (~5h)</li>
        <li>Redis/Route caching layer (~4h)</li>
        <li>Staging benchmarking & go-live validation (~5h)</li>
      </ul>
    </div>
  </div>

  <div style="background: rgba(0,0,0,0.3); border: 1px solid var(--border); border-radius: 8px; padding: 14px; font-size: 13.5px; line-height: 1.6; color: #e5e7eb;">
    <div style="font-size: 11px; font-family: var(--font-mono); color: var(--emerald-light); text-transform: uppercase; margin-bottom: 6px;">AI Generated Proposal:</div>
    "Your Laravel app's speed issues are affecting real users — that needs a methodical fix, not guesswork. I'll profile the bottlenecks first (slow queries, missing indexes, unoptimized Eloquent calls) before touching anything in production..."
  </div>
</div>

<!-- Workflow Steps -->
<div class="glass-panel" style="margin-bottom: 60px;">
  <h2 style="text-align: center; margin-bottom: 24px; font-size: 22px;">How FirstBid AI Automates Your Bidding</h2>
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; text-align: center;">
    <div style="padding: 12px; background: rgba(255,255,255,0.02); border-radius: 8px; border: 1px solid var(--border);">
      <div style="font-family: var(--font-mono); color: var(--emerald); font-size: 12px; margin-bottom: 4px;">STEP 01</div>
      <div style="font-weight: 600; color: #fff; font-size: 15px;">Job Webhook Ping</div>
      <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 4px;">UpHunt / Feed catches posting in seconds</div>
    </div>
    <div style="padding: 12px; background: rgba(255,255,255,0.02); border-radius: 8px; border: 1px solid var(--border);">
      <div style="font-family: var(--font-mono); color: var(--emerald); font-size: 12px; margin-bottom: 4px;">STEP 02</div>
      <div style="font-weight: 600; color: #fff; font-size: 15px;">Verification & Filtering</div>
      <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 4px;">Unverified payment & low scores skipped</div>
    </div>
    <div style="padding: 12px; background: rgba(255,255,255,0.02); border-radius: 8px; border: 1px solid var(--border);">
      <div style="font-family: var(--font-mono); color: var(--emerald); font-size: 12px; margin-bottom: 4px;">STEP 03</div>
      <div style="font-weight: 600; color: #fff; font-size: 15px;">AI Budget Calculation</div>
      <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 4px;">Subtasks, hours & price estimated</div>
    </div>
    <div style="padding: 12px; background: rgba(255,255,255,0.02); border-radius: 8px; border: 1px solid var(--border);">
      <div style="font-family: var(--font-mono); color: var(--emerald); font-size: 12px; margin-bottom: 4px;">STEP 04</div>
      <div style="font-weight: 600; color: #fff; font-size: 15px;">Proposal Generation</div>
      <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 4px;">Auto or single-click on demand</div>
    </div>
    <div style="padding: 12px; background: rgba(255,255,255,0.02); border-radius: 8px; border: 1px solid var(--border);">
      <div style="font-family: var(--font-mono); color: var(--emerald); font-size: 12px; margin-bottom: 4px;">STEP 05</div>
      <div style="font-weight: 600; color: #fff; font-size: 15px;">Telegram Notification</div>
      <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 4px;">Copy proposal & submit on Upwork</div>
    </div>
  </div>
</div>

<!-- Features Grid -->
<div id="features" style="margin-bottom: 60px;">
  <h2 style="text-align: center; font-size: 26px; margin-bottom: 8px;">Everything You Need to Win High-Paying Jobs</h2>
  <p style="text-align: center; color: var(--text-muted); margin-bottom: 32px;">Designed for professional freelancers who want speed, accuracy, and account safety.</p>

  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
    <div class="glass-panel" style="margin: 0;">
      <div style="font-size: 28px; margin-bottom: 10px;">📊</div>
      <h3 style="font-size: 18px; color: #fff; margin-bottom: 8px;">AI Budget & Timeline Calculator</h3>
      <p style="color: var(--text-muted); font-size: 14px;">Calculates realistic subtask hours, project duration, and pricing strategies. Detects placeholder $100 client budgets for 10+ day jobs and suggests milestone breakdowns.</p>
    </div>

    <div class="glass-panel" style="margin: 0;">
      <div style="font-size: 28px; margin-bottom: 10px;">💳</div>
      <h3 style="font-size: 18px; color: #fff; margin-bottom: 8px;">Payment Verification Filter</h3>
      <p style="color: var(--text-muted); font-size: 14px;">Filters out unverified client listings automatically. Protect your Connects and letter quota by only focusing on serious, verified clients.</p>
    </div>

    <div class="glass-panel" style="margin: 0;">
      <div style="font-size: 28px; margin-bottom: 10px;">⚡</div>
      <h3 style="font-size: 18px; color: #fff; margin-bottom: 8px;">Auto vs. On-Demand Proposals</h3>
      <p style="color: var(--text-muted); font-size: 14px;">Full control over letter quota. Choose to auto-write proposals on alert arrival or capture jobs and generate proposals manually with 1 click.</p>
    </div>

    <div class="glass-panel" style="margin: 0;">
      <div style="font-size: 28px; margin-bottom: 10px;">🛡️</div>
      <h3 style="font-size: 18px; color: #fff; margin-bottom: 8px;">Account Security & Admin Controls</h3>
      <p style="color: var(--text-muted); font-size: 14px;">Zero risk of Upwork bans. FirstBid never logs into Upwork or auto-submits. New registrations require Admin approval for platform safety.</p>
    </div>

    <div class="glass-panel" style="margin: 0;">
      <div style="font-size: 28px; margin-bottom: 10px;">📱</div>
      <h3 style="font-size: 18px; color: #fff; margin-bottom: 8px;">Real-Time Telegram & Bell Alerts</h3>
      <p style="color: var(--text-muted); font-size: 14px;">Instant alerts sent straight to your phone and dashboard. Open the alert, copy the AI proposal, and submit before competitors even see the job.</p>
    </div>

    <div class="glass-panel" style="margin: 0;">
      <div style="font-size: 28px; margin-bottom: 10px;">📝</div>
      <h3 style="font-size: 18px; color: #fff; margin-bottom: 8px;">Screening Questions Answered</h3>
      <p style="color: var(--text-muted); font-size: 14px;">Required Upwork screening questions are automatically answered based on your real past experience and profile skills.</p>
    </div>
  </div>
</div>

<!-- Pricing Section -->
<div class="glass-panel" style="max-width: 480px; margin: 0 auto 60px; text-align: center; border-color: var(--emerald);">
  <div style="font-size: 12px; font-family: var(--font-mono); text-transform: uppercase; color: var(--emerald-light); margin-bottom: 6px;">Early Access Pricing</div>
  <div style="font-size: 44px; font-weight: 800; color: #fff;">₹0</div>
  <div style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 20px;">Free for 30 Days · No Credit Card Required</div>

  <ul style="text-align: left; list-style: none; margin-bottom: 24px; font-size: 14px; color: var(--text-main);">
    <li style="padding: 6px 0; border-bottom: 1px solid var(--border);">✓ Up to 100 AI Proposals / month</li>
    <li style="padding: 6px 0; border-bottom: 1px solid var(--border);">✓ AI Budget & Timeline Estimator</li>
    <li style="padding: 6px 0; border-bottom: 1px solid var(--border);">✓ Payment Verification Filtering</li>
    <li style="padding: 6px 0; border-bottom: 1px solid var(--border);">✓ Telegram Alerts & Unread Bell Dropdown</li>
    <li style="padding: 6px 0;">✓ Works with UpHunt, Vibeworker & Webhooks</li>
  </ul>

  <a class="btn" href="{{ route('register') }}" style="width: 100%; padding: 14px; font-size: 16px;">Start 30-Day Free Trial ↗</a>
</div>
@endsection
