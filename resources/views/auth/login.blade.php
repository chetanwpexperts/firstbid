@extends('layout')

@section('title', 'Log In — FirstBid')

@section('content')
<div style="max-width: 440px; margin: 40px auto 0;">
  <div class="glass-panel" style="padding: 36px 30px;">
    <h1 style="font-size: 24px; font-weight: 800; color: var(--text-dark); margin-bottom: 6px; text-align: center;">Welcome Back</h1>
    <p style="font-size: 14px; color: var(--text-muted); text-align: center; margin-bottom: 26px;">Log in to access your AI job inbox & proposal tools.</p>

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="form-group">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com">
      </div>

      <div class="form-group">
        <label class="form-label">Password</label>
        <input type="password" name="password" required placeholder="••••••••">
      </div>

      <div style="margin-top: 24px;">
        <button class="btn" type="submit" style="width: 100%; padding: 12px; font-size: 15px;">Log In ↗</button>
      </div>
    </form>

    <p style="font-size: 13.5px; color: var(--text-muted); text-align: center; margin-top: 22px;">
      Don't have an account? <a href="{{ route('register') }}" style="font-weight: 600;">Start 30-Day Free Trial</a>
    </p>
  </div>
</div>
@endsection
