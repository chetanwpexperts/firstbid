@extends('layout')

@section('title', 'FirstBid Chrome Extension — Coming Soon | 100% Upwork ToS Compliant')

@section('content')
<div style="text-align: center; padding: 40px 0 50px;">
  <div style="display: inline-flex; align-items: center; gap: 8px; background: var(--upwork-tint); border: 1px solid var(--upwork-tint-border); color: var(--upwork-tint-text); font-family: var(--font-mono); font-size: 12px; padding: 6px 16px; border-radius: 20px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 24px;">
    🚀 Manifest V3 · 100% Upwork ToS Compliant · Account Safe
  </div>

  <h1 style="font-size: clamp(32px, 5vw, 52px); font-weight: 800; line-height: 1.15; letter-spacing: -0.03em; max-width: 860px; margin: 0 auto 20px; color: var(--upwork-deep);">
    FirstBid for Chrome — <span style="color: var(--upwork-green);">Coming Soon</span>
  </h1>

  <p style="font-size: 18px; color: var(--text-muted); max-width: 680px; margin: 0 auto 32px; line-height: 1.6;">
    Draft tailored proposals and answer screening questions directly inside Upwork job pages in 1 click — without leaving your browser tab.
  </p>

  <div style="display: inline-block; background: #ffffff; border: 2px solid var(--upwork-green); border-radius: 14px; padding: 24px 32px; box-shadow: 0 10px 30px rgba(20,168,0,0.15); max-width: 520px; width: 100%;">
    <div style="font-size: 13px; font-family: var(--font-mono); text-transform: uppercase; color: var(--upwork-green); font-weight: 800; margin-bottom: 8px;">🎁 VIP Early Beta Access</div>
    <h2 style="font-size: 20px; font-weight: 800; color: var(--text-dark); margin-bottom: 8px;">Join the Chrome Extension Waitlist</h2>
    <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 20px;">Be the first to get 1-click proposal auto-fill when the Chrome Extension launches.</p>

    @auth
      <form method="POST" action="{{ route('feedback.store') }}">
        @csrf
        <input type="hidden" name="rating" value="5">
        <input type="hidden" name="category" value="feature_request">
        <input type="hidden" name="message" value="Waitlist signup for Chrome Extension early access. User: {{ auth()->user()->email }}">
        <button class="btn" type="submit" style="width: 100%; padding: 14px; font-size: 15.5px;">⚡ Request Early Beta Access</button>
      </form>
    @else
      <a class="btn" href="{{ route('register') }}" style="display: block; width: 100%; padding: 14px; font-size: 15.5px;">Start Free Trial to Join Waitlist ↗</a>
    @endauth
  </div>
</div>

<!-- Extension Feature Mockup Preview -->
<div class="glass-panel" style="max-width: 820px; margin: 0 auto 60px; border-color: var(--upwork-tint-border); background: #ffffff; padding: 32px;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid var(--border); padding-bottom: 16px; flex-wrap: wrap; gap: 12px;">
    <div style="display: flex; align-items: center; gap: 10px;">
      <span style="font-size: 24px;">🧩</span>
      <div>
        <div style="font-weight: 800; font-size: 18px; color: var(--text-dark);">Upwork Job Page Overlay (Preview)</div>
        <div style="font-size: 13px; color: var(--text-muted);">How FirstBid integrates directly into your Upwork workflow</div>
      </div>
    </div>
    <span class="badge badge-notified" style="font-size: 12px;">100% Legal & Safe</span>
  </div>

  <!-- Simulated Upwork Job UI -->
  <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 12px; padding: 24px; margin-bottom: 24px;">
    <div style="font-size: 12px; font-family: var(--font-mono); color: var(--text-muted); margin-bottom: 6px;">UPWORK JOB PAGE</div>
    <div style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 12px;">Full-Stack Laravel Developer Needed for SaaS Optimization</div>

    <div style="background: var(--upwork-tint); border: 1px solid var(--upwork-tint-border); border-radius: 8px; padding: 16px; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 20px;">⚡</span>
        <div>
          <div style="font-weight: 800; font-size: 14px; color: var(--upwork-deep);">FirstBid Extension Assistant</div>
          <div style="font-size: 12.5px; color: var(--upwork-tint-text);">AI Scope: ~16 hrs ($45/hr) · 3 Opener Hooks Ready</div>
        </div>
      </div>
      <button class="btn btn-sm" type="button" style="padding: 8px 16px; font-size: 13px;">Auto-Fill Proposal into Textbox 🪄</button>
    </div>

    <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 8px; padding: 14px; font-size: 13.5px; color: var(--text-dark); line-height: 1.5;">
      <b>Cover Letter Textarea:</b><br>
      <span style="color: var(--text-muted); font-style: italic;">"Your Laravel app's speed issues are affecting real users — that needs a methodical fix, not guesswork. I'll profile the bottlenecks first (slow queries, missing indexes, unoptimized Eloquent calls)..."</span>
    </div>
  </div>

  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
    <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px; padding: 18px;">
      <div style="font-size: 22px; margin-bottom: 8px;">✋</div>
      <div style="font-weight: 700; color: var(--text-dark); margin-bottom: 4px; font-size: 15px;">Human-in-the-Loop</div>
      <div style="font-size: 13px; color: var(--text-muted);">FirstBid writes proposal drafts; you review and manually click Submit. Zero risk of bot bidding.</div>
    </div>

    <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px; padding: 18px;">
      <div style="font-size: 22px; margin-bottom: 8px;">🔐</div>
      <div style="font-weight: 700; color: var(--text-dark); margin-bottom: 4px; font-size: 15px;">Zero Upwork Passwords</div>
      <div style="font-size: 13.5px; color: var(--text-muted);">Never asks for your Upwork credentials. Authenticates safely via your FirstBid account API key.</div>
    </div>

    <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px; padding: 18px;">
      <div style="font-size: 22px; margin-bottom: 8px;">⚡</div>
      <div style="font-weight: 700; color: var(--text-dark); margin-bottom: 4px; font-size: 15px;">Screening Answer Helper</div>
      <div style="font-size: 13.5px; color: var(--text-muted);">Automatically populates draft answers for client screening questions based on your experience.</div>
    </div>
  </div>
</div>
@endsection
