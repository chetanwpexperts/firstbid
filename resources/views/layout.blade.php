<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'FirstBid AI — Automated Upwork Job Alerts & Proposals')</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
  @include('partials.header')

  <main class="app-wrap">
    @include('partials.flash')
    @yield('content')
  </main>

  @include('partials.footer')

  <script>
  function copyVal(id, btn){
    const el = document.getElementById(id);
    if (!el) return;
    navigator.clipboard.writeText(el.value || el.innerText).then(() => {
      const old = btn.innerText; btn.innerText = 'Copied ✓';
      setTimeout(() => btn.innerText = old, 1500);
    });
  }
  function copyText(id, btn){ copyVal(id, btn); }
  </script>
</body>
</html>
