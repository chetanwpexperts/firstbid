<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'FirstBid')</title>
<style>
:root{
  --bg:#f6f7f4; --panel:#fff; --ink:#1b2420; --muted:#5d6b63;
  --line:#dde3dc; --green:#14a800; --green-dark:#0e7a00;
  --amber:#b45309; --red:#c02626;
  --mono:ui-monospace,'SF Mono',Menlo,Consolas,monospace;
}
*{box-sizing:border-box;margin:0;padding:0}
body{background:var(--bg);color:var(--ink);font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;min-height:100vh;font-size:15px;line-height:1.55}
a{color:var(--green-dark)}
.nav{background:var(--panel);border-bottom:1px solid var(--line);padding:0 20px;display:flex;align-items:center;gap:22px;height:56px}
.nav .brand{font-weight:700;font-size:17px;letter-spacing:-.02em;color:var(--ink);text-decoration:none}
.nav .brand span{color:var(--green)}
.nav a.link{color:var(--muted);text-decoration:none;font-size:14px}
.nav a.link:hover,.nav a.link.active{color:var(--ink)}
.nav form{margin-left:auto}
.wrap{max-width:1000px;margin:0 auto;padding:26px 16px 60px}
.panel{background:var(--panel);border:1px solid var(--line);border-radius:10px;padding:20px;margin-bottom:18px}
h1{font-size:22px;margin-bottom:16px;letter-spacing:-.02em}
h2{font-size:12px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.08em;color:var(--muted);margin-bottom:10px}
label{display:block;font-size:13px;font-weight:600;margin:14px 0 6px}
input[type=text],input[type=email],input[type=password],input[type=number],textarea{width:100%;border:1px solid var(--line);border-radius:7px;padding:10px 12px;font-size:14px;font-family:inherit;background:#fbfcfa}
textarea{font-family:var(--mono);font-size:13px;line-height:1.5;resize:vertical}
input:focus,textarea:focus{outline:2px solid var(--green);outline-offset:-1px;border-color:transparent}
.btn{display:inline-block;background:var(--green);color:#fff;border:none;border-radius:7px;padding:11px 20px;font-size:14px;font-weight:600;cursor:pointer;text-decoration:none}
.btn:hover{background:var(--green-dark)}
.btn.ghost{background:#fff;color:var(--ink);border:1px solid var(--line)}
.btn.ghost:hover{border-color:var(--green);color:var(--green-dark);background:#fff}
.btn.sm{padding:6px 12px;font-size:12px}
.flash{border-radius:8px;padding:11px 14px;font-size:14px;margin-bottom:16px}
.flash.ok{background:#ecfdf0;border:1px solid #bbe8c4;color:#14652a}
.flash.err{background:#fef2f2;border:1px solid #fecaca;color:var(--red)}
.help{font-size:12.5px;color:var(--muted);margin-top:6px;line-height:1.55}
code.k{font-family:var(--mono);font-size:12.5px;background:#eef2ec;border:1px solid var(--line);border-radius:5px;padding:2px 7px;word-break:break-all}
.copyrow{display:flex;gap:8px;align-items:center;margin-top:6px}
.copyrow input{font-family:var(--mono);font-size:12.5px}
.codebox{display:flex;align-items:center;gap:10px;background:#fbfcfa;border:1px solid var(--line);border-radius:7px;padding:10px 12px;font-family:var(--mono);font-size:13px;word-break:break-all}
.codebox span{flex:1}
.copybtn{flex:none;background:#fff;border:1px solid var(--line);border-radius:6px;padding:5px 11px;font-size:12px;cursor:pointer}
.copybtn:hover{border-color:var(--green);color:var(--green-dark)}
.window-tabs{display:flex;gap:6px;margin-bottom:16px;flex-wrap:wrap}
.window-tabs a{font-family:var(--mono);font-size:12.5px;padding:6px 12px;border:1px solid var(--line);border-radius:20px;text-decoration:none;color:var(--muted);background:#fff}
.window-tabs a:hover{border-color:var(--green);color:var(--green-dark)}
.window-tabs a.on{background:var(--ink);color:#fff;border-color:var(--ink)}
.statgrid{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:12px;margin-bottom:18px}
.stat{background:var(--panel);border:1px solid var(--line);border-radius:10px;padding:14px;text-align:center}
.stat .n{font-family:var(--mono);font-size:26px;font-weight:700}
.stat .l{font-size:11px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.07em;color:var(--muted)}
.job{border:1px solid var(--line);border-radius:10px;background:var(--panel);margin-bottom:12px;overflow:hidden}
.job summary{list-style:none;cursor:pointer;padding:14px 16px;display:flex;gap:12px;align-items:center;flex-wrap:wrap}
.job summary::-webkit-details-marker{display:none}
.score{font-family:var(--mono);font-weight:700;font-size:15px;border-radius:7px;padding:4px 9px;color:#fff;flex:none}
.s-hi{background:var(--green)} .s-mid{background:var(--amber)} .s-low{background:#9ca3af}
.job .t{font-weight:600;flex:1;min-width:200px}
.job .meta{font-size:12.5px;color:var(--muted);font-family:var(--mono)}
.badge{font-size:11px;font-family:var(--mono);border-radius:6px;padding:3px 8px;border:1px solid var(--line);color:var(--muted)}
.badge.notified{border-color:#bbe8c4;color:#14652a;background:#ecfdf0}
.badge.skipped{background:#f5f5f4}
.badge.failed{border-color:#fecaca;color:var(--red);background:#fef2f2}
.badge.ready_to_generate{border-color:#fde68a;color:var(--amber);background:#fffbeb}
.job .body{border-top:1px solid var(--line);padding:16px}
.letter{white-space:pre-wrap;background:#fbfcfa;border:1px solid var(--line);border-radius:8px;padding:14px;font-size:14px;line-height:1.65;margin-top:8px}
.flagline{font-size:13px;margin:3px 0}
.qa{margin-top:14px}
.qa .q{font-weight:600;font-size:13.5px;margin-top:10px}
.pager{display:flex;justify-content:center;align-items:center;gap:4px;margin-top:24px;flex-wrap:wrap}
.pager a,.pager span{display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 9px;border:1px solid var(--line);border-radius:7px;font-size:13px;font-family:var(--mono);text-decoration:none;background:#fff;color:var(--ink)}
.pager a:hover{border-color:var(--green);color:var(--green-dark)}
.pager .cur{background:var(--green);color:#fff;border-color:var(--green);font-weight:700}
.pager .disabled{color:#b7c0ba;background:#fbfcfa;cursor:default}
.pager .dots{border-color:transparent;background:none;color:var(--muted)}
@media (max-width:480px){.pager a,.pager span{min-width:28px;height:28px;font-size:11.5px;padding:0 6px}}
.auth-card{max-width:420px;margin:8vh auto 0}
.auth-card .brand{font-weight:700;font-size:24px;text-align:center;display:block;margin-bottom:22px;color:var(--ink);text-decoration:none}
.auth-card .brand span{color:var(--green)}
.err-list{color:var(--red);font-size:13px;margin-top:6px}
</style>
</head>
<body>
@auth
<nav class="nav">
  <a class="brand" href="{{ route('dashboard') }}">First<span>Bid</span></a>
  <a class="link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Jobs</a>
  <a class="link {{ request()->routeIs('settings') ? 'active' : '' }}" href="{{ route('settings') }}">Settings</a>
  <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn ghost sm" type="submit">Log out</button></form>
</nav>
@endauth
<div class="wrap">
  @if(session('ok'))<div class="flash ok">{{ session('ok') }}</div>@endif
  @if($errors->any())<div class="flash err">{{ $errors->first() }}</div>@endif
  @yield('content')
</div>
<script>
function copyVal(id, btn){
  const el = document.getElementById(id);
  navigator.clipboard.writeText(el.value || el.innerText).then(() => {
    const old = btn.innerText; btn.innerText = 'Copied ✓';
    setTimeout(() => btn.innerText = old, 1500);
  });
}
function copyText(id, btn){ copyVal(id, btn); }
</script>
</body>
</html>
