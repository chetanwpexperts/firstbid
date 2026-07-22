@extends('layout')

@section('title', 'Log In — FirstBid AI')

@section('content')
<div style="max-width: 420px; margin: 60px auto 0;">
  <div class="glass-panel" style="padding: 32px 28px;">
    <h1 style="font-size: 22px; font-weight: 800; color: #fff; margin-bottom: 6px; text-align: center;">Welcome Back</h1>
    <p style="font-size: 13.5px; color: var(--text-muted); text-align: center; margin-bottom: 24px;">Log in to access your AI job inbox & proposal tools.</p>

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

      <div style="margin-top: 22px;">
        <button class="btn" type="submit" style="width: 100%; padding: 12px; font-size: 15px;">Log In ↗</button>
      </div>
    </form>

    <p style="font-size: 13px; color: var(--text-muted); text-align: center; margin-top: 20px;">
      Don't have an account? <a href="{{ route('register') }}" style="color: var(--emerald-light);">Start 30-Day Free Trial</a>
    </p>
  </div>
</div>
@endsection
