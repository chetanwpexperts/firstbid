<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'FirstBid — Upwork AI Job Proposal & Scope Estimator')</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
  <!-- Top Progress Bar Loader -->
  <div id="pageLoader"></div>

  @include('partials.header')

  <main class="app-wrap">
    @include('partials.flash')
    @yield('content')
  </main>

  @include('partials.footer')

  <script>
  // Global Page Navigation Loader Bar
  window.addEventListener('beforeunload', function() {
    startLoader();
  });

  function startLoader() {
    const l = document.getElementById('pageLoader');
    if (!l) return;
    l.style.opacity = '1';
    l.style.width = '30%';
    setTimeout(() => { l.style.width = '70%'; }, 200);
    setTimeout(() => { l.style.width = '90%'; }, 500);
  }

  function finishLoader() {
    const l = document.getElementById('pageLoader');
    if (!l) return;
    l.style.width = '100%';
    setTimeout(() => {
      l.style.opacity = '0';
      setTimeout(() => { l.style.width = '0%'; }, 300);
    }, 200);
  }

  document.addEventListener('DOMContentLoaded', function() {
    finishLoader();

    // Trigger loader on all internal link clicks and forms
    document.querySelectorAll('a[href]').forEach(a => {
      a.addEventListener('click', function(e) {
        if (a.hostname === window.location.hostname && !a.getAttribute('target') && !a.getAttribute('href').startsWith('#') && !a.getAttribute('href').startsWith('javascript:')) {
          startLoader();
        }
      });
    });

    document.querySelectorAll('form').forEach(f => {
      f.addEventListener('submit', function() {
        startLoader();
      });
    });
  });

  function copyVal(id, btn) {
    const el = document.getElementById(id);
    if (!el) return;
    navigator.clipboard.writeText(el.value || el.innerText).then(() => {
      const old = btn.innerText; btn.innerText = 'Copied ✓';
      setTimeout(() => btn.innerText = old, 1500);
    });
  }
  function copyText(id, btn) { copyVal(id, btn); }
  </script>
</body>
</html>
