@extends('layout')

@section('title', 'Pending Approval — FirstBid')

@section('content')
<div style="max-width: 480px; margin: 60px auto 0; text-align: center;">
  <div class="glass-panel" style="padding: 44px 30px;">
    <div style="font-size: 48px; margin-bottom: 14px;">⌛</div>
    <h1 style="font-size: 24px; font-weight: 800; color: var(--text-dark); margin-bottom: 10px;">Account Pending Approval</h1>
    <p style="font-size: 14.5px; color: var(--text-muted); margin-bottom: 26px; line-height: 1.6;">
      Thank you for registering! Your account has been created and is currently awaiting administrator review.
      <br><br>
      You will be granted full access to the AI dashboard and job alert webhooks as soon as an administrator approves your account.
    </p>

    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="btn btn-ghost">Log Out</button>
    </form>
  </div>
</div>
@endsection
