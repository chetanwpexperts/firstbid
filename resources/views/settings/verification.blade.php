@extends('layout')
@section('title', 'Confirm forwarding — FirstBid')
@section('content')
<p style="margin-bottom:10px"><a href="{{ route('settings') }}" style="color:var(--muted);text-decoration:none">← Back to settings</a></p>
<h1>Confirm Gmail forwarding</h1>
<p class="help" style="margin-bottom:16px">This is the verification email Gmail sent when you set up forwarding. Click the confirm button/link inside it to activate forwarding to your FirstBid inbox.</p>

<div class="panel" style="padding:0;overflow:hidden">
  <iframe srcdoc="{{ $email->html }}" sandbox="allow-popups allow-popups-to-escape-sandbox" style="width:100%;height:70vh;border:0;background:#fff"></iframe>
</div>
@endsection
