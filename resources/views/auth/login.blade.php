@extends('layout')
@section('title', 'Log in — FirstBid')
@section('content')
<div class="auth-card">
  <a class="brand" href="/">First<span>Bid</span></a>
  <div class="panel">
    <h1>Log in</h1>
    <form method="POST" action="{{ route('login') }}">
      @csrf
      <label>Email</label>
      <input type="email" name="email" value="{{ old('email') }}" required>
      <label>Password</label>
      <input type="password" name="password" required>
      <div style="margin-top:18px"><button class="btn" type="submit">Log in</button></div>
    </form>
    <p class="help" style="margin-top:14px">New here? <a href="{{ route('register') }}">Create an account</a></p>
  </div>
</div>
@endsection
