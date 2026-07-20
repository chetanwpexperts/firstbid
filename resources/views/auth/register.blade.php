@extends('layout')
@section('title', 'Create account — FirstBid')
@section('content')
<div class="auth-card">
  <a class="brand" href="/">First<span>Bid</span></a>
  <div class="panel">
    <h1>Create account</h1>
    <form method="POST" action="{{ route('register') }}">
      @csrf
      <label>Name</label>
      <input type="text" name="name" value="{{ old('name') }}" required>
      <label>Email</label>
      <input type="email" name="email" value="{{ old('email') }}" required>
      <label>Password</label>
      <input type="password" name="password" required minlength="8">
      <label>Confirm password</label>
      <input type="password" name="password_confirmation" required minlength="8">
      <div style="margin-top:18px"><button class="btn" type="submit">Sign up</button></div>
    </form>
    <p class="help" style="margin-top:14px">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
  </div>
</div>
@endsection
