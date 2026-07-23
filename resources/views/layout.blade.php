<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="Account-safe Upwork AI proposal generator & scope budget calculator. Real-time job alerts, mathematical subtask estimates, and tailored cover letters.">
<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="@yield('title', 'FirstBidIn — Upwork AI Proposal Generator & Budget Estimator')">
<meta property="og:description" content="Win Upwork jobs in 2 minutes with AI scope breakdowns, subtask effort estimates, and account-safe proposal writing.">
<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:title" content="@yield('title', 'FirstBidIn — Upwork AI Proposal Generator & Budget Estimator')">
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
  "name": "FirstBidIn AI",
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
  @include('partials.onboarding-tour-modal')

  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
