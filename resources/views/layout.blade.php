<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Account-safe Upwork AI proposal generator & scope budget calculator. Real-time job alerts, mathematical subtask estimates, and tailored cover letters.">
<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="@yield('title', 'FirstBid — Upwork AI Proposal Generator & Budget Estimator')">
<meta property="og:description" content="Win Upwork jobs in 2 minutes with AI scope breakdowns, subtask effort estimates, and account-safe proposal writing.">
<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:title" content="@yield('title', 'FirstBid — Upwork AI Proposal Generator & Budget Estimator')">
<meta property="twitter:description" content="Win Upwork jobs in 2 minutes with AI scope breakdowns, subtask effort estimates, and account-safe proposal writing.">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">

@php $gaId = config('services.ga.measurement_id'); @endphp
@if ($gaId)
<!-- Google Analytics GA4 -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{{ $gaId }}');
</script>
@endif

<!-- Schema.org JSON-LD Structured Data -->
<script type="application/ld+json">
{
  "{{ '@' }}context": "https://schema.org",
  "{{ '@' }}type": "SoftwareApplication",
  "name": "FirstBid AI",
  "operatingSystem": "Web",
  "applicationCategory": "BusinessApplication",
  "description": "Account-safe Upwork AI Proposal Generator, Scope & Budget Estimator for Freelancers.",
  "offers": {
    "{{ '@' }}type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  }
}
</script>
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
  @include('partials.feedback-modal')

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
