@extends('layout')

@section('title', 'Start Free Trial — FirstBid AI')

@section('content')
<div style="max-width: 440px; margin: 40px auto 0;">
  <div class="glass-panel" style="padding: 32px 28px;">
    <h1 style="font-size: 22px; font-weight: 800; color: #fff; margin-bottom: 6px; text-align: center;">Start 30-Day Free Trial</h1>
    <p style="font-size: 13.5px; color: var(--text-muted); text-align: center; margin-bottom: 24px;">Create your account to start receiving AI proposals & budget estimates.</p>

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
        <label class="form-label">Password</label>
        <input type="password" name="password" required minlength="8" placeholder="At least 8 characters">
      </div>

      <div class="form-group">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" required minlength="8" placeholder="Re-enter password">
      </div>

      <div style="margin-top: 22px;">
        <button class="btn" type="submit" style="width: 100%; padding: 12px; font-size: 15px;">Create Free Account ↗</button>
      </div>
    </form>

    <p style="font-size: 13px; color: var(--text-muted); text-align: center; margin-top: 20px;">
      Already have an account? <a href="{{ route('login') }}" style="color: var(--emerald-light);">Log In</a>
    </p>
  </div>
</div>
@endsection
