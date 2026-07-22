@extends('layout')

@section('title', 'Start Free Trial — FirstBid')

@section('content')
<div style="max-width: 480px; margin: 40px auto 0;">
  <div class="glass-panel" style="padding: 36px 30px;">
    <h1 style="font-size: 24px; font-weight: 800; color: var(--text-dark); margin-bottom: 6px; text-align: center;">Start 30-Day Free Trial</h1>
    <p style="font-size: 14px; color: var(--text-muted); text-align: center; margin-bottom: 26px;">Create your account to start receiving AI proposals & budget estimates.</p>

    <form method="POST" action="{{ route('register') }}">
      @csrf
      <div class="form-group">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Jane Doe">
      </div>

      <div class="form-group">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com">
      </div>

      <div class="form-group">
        <label class="form-label">Primary Upwork Niche / Specialty</label>
        <input type="text" name="niche" value="{{ old('niche') }}" placeholder="e.g. Full-Stack Laravel Developer, Mobile App Specialist">
      </div>

      <div class="form-group">
        <label class="form-label">Password</label>
        <input type="password" name="password" required minlength="8" placeholder="At least 8 characters">
      </div>

      <div class="form-group">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" required minlength="8" placeholder="Re-enter password">
      </div>

      <!-- Mandatory Terms of Service Checkbox -->
      <div style="margin-top: 18px;">
        <label style="display: flex; align-items: flex-start; gap: 10px; font-size: 13px; color: var(--text-muted); cursor: pointer; line-height: 1.45;">
          <input type="checkbox" name="terms" required style="margin-top: 2px;">
          <span>I agree to FirstBid's <b>Terms of Service</b> and <b>Account Security Policy</b> (Human-in-the-Loop Writing Assistance only, zero auto-submit bots).</span>
        </label>
      </div>

      <div style="margin-top: 24px;">
        <button class="btn" type="submit" style="width: 100%; padding: 12px; font-size: 15px;">Create Free Account ↗</button>
      </div>
    </form>

    <p style="font-size: 13.5px; color: var(--text-muted); text-align: center; margin-top: 22px;">
      Already have an account? <a href="{{ route('login') }}" style="font-weight: 600;">Log In</a>
    </p>
  </div>
</div>
@endsection
