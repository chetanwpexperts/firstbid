@extends('layout')

@section('title', 'Pending Approval — FirstBid')

@section('content')
<div class="auth-card" style="text-align: center;">
  <a class="brand" href="/">First<span>Bid</span></a>
  
  <div class="panel" style="padding: 30px 20px;">
    <div style="font-size: 44px; margin-bottom: 12px;">⌛</div>
    <h1 style="font-size: 20px; margin-bottom: 10px;">Account Pending Approval</h1>
    <p class="help" style="font-size: 14px; color: var(--muted); margin-bottom: 20px; line-height: 1.6;">
      Thank you for registering! Your account has been created and is currently awaiting administrator review. 
      <br><br>
      You will be granted full access to the dashboard and automated job alerts as soon as an administrator approves your account.
    </p>

    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="btn ghost">Log Out</button>
    </form>
  </div>
</div>
@endsection
