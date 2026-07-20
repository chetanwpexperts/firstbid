<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FirstBid — Apply to Upwork jobs in 2 minutes, without risking your account</title>
<meta name="description" content="Real-time job alerts + AI cover letters written in your voice, delivered to your Telegram. You review and submit — no bots touch your Upwork account.">
<style>
:root{--bg:#f6f7f4;--panel:#fff;--ink:#1b2420;--muted:#5d6b63;--line:#dde3dc;--green:#14a800;--green-dark:#0e7a00;--amber:#b45309;
--mono:'SF Mono',ui-monospace,Menlo,Consolas,monospace;--sans:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif}
*{box-sizing:border-box;margin:0;padding:0}
body{background:var(--bg);color:var(--ink);font-family:var(--sans);font-size:16px;line-height:1.6}
a{color:inherit}
.nav{background:var(--panel);border-bottom:2px solid var(--ink);padding:0 24px;display:flex;align-items:center;gap:20px;height:58px;position:sticky;top:0;z-index:10}
.brand{font-weight:700;font-size:19px;letter-spacing:-.02em;text-decoration:none}
.brand span{color:var(--green)}
.nav .spacer{flex:1}
.btn{display:inline-block;background:var(--green);color:#fff;border:none;border-radius:6px;padding:11px 22px;font-size:15px;font-weight:600;cursor:pointer;text-decoration:none}
.btn:hover{background:var(--green-dark)}
.btn.secondary{background:var(--panel);color:var(--ink);border:1px solid var(--line)}
.btn.secondary:hover{border-color:var(--ink)}
.btn.big{padding:14px 30px;font-size:17px}
.wrap{max-width:1040px;margin:0 auto;padding:0 20px}
.hero{padding:76px 0 60px;text-align:center}
.hero .eyebrow{font-family:var(--mono);font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--green-dark);background:#ecf9ec;border:1px solid #bfe8bf;display:inline-block;padding:5px 14px;border-radius:14px;margin-bottom:22px}
.hero h1{font-size:clamp(30px,5.4vw,52px);line-height:1.12;letter-spacing:-.03em;max-width:820px;margin:0 auto 18px}
.hero h1 em{font-style:normal;color:var(--green)}
.hero p.sub{font-size:19px;color:var(--muted);max-width:640px;margin:0 auto 30px}
.hero .cta-row{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.hero .note{font-size:13px;color:var(--muted);margin-top:14px}
.flow{background:var(--ink);color:#eef2ee;padding:50px 0}
.flow .wrap{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:0;text-align:center}
.flow .step{padding:10px 14px;position:relative}
.flow .step:not(:last-child):after{content:"→";position:absolute;right:-9px;top:34px;color:var(--green);font-size:20px}
.flow .n{font-family:var(--mono);color:var(--green);font-size:13px;margin-bottom:6px}
.flow .t{font-weight:600;font-size:15.5px}
.flow .d{font-size:13px;color:#a9b5ab;margin-top:4px}
section.block{padding:64px 0}
section.block h2{font-size:clamp(24px,3.4vw,34px);letter-spacing:-.02em;margin-bottom:10px;text-align:center}
section.block p.lead{text-align:center;color:var(--muted);max-width:620px;margin:0 auto 40px;font-size:17px}
.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px}
.card{background:var(--panel);border:1px solid var(--line);border-radius:10px;padding:24px}
.card .ico{font-size:26px;margin-bottom:10px}
.card h3{font-size:17px;margin-bottom:6px}
.card p{font-size:14.5px;color:var(--muted)}
.safe{background:var(--panel);border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
.safe .grid{display:grid;grid-template-columns:1fr 1fr;gap:34px;align-items:center}
@media(max-width:760px){.safe .grid{grid-template-columns:1fr}}
.safe h2{text-align:left!important}
.safe ul{list-style:none;margin-top:16px}
.safe li{padding:9px 0 9px 30px;position:relative;font-size:15.5px}
.safe li:before{content:"✓";position:absolute;left:0;color:var(--green);font-weight:700}
.safe li.no:before{content:"✕";color:#c02626}
.tgcard{background:var(--ink);border-radius:14px;padding:22px;color:#eef2ee;font-size:14px;line-height:1.65;max-width:420px;margin:0 auto;font-family:var(--mono)}
.tgcard .hd{color:var(--green);margin-bottom:8px}
.tgcard .btnrow{margin-top:12px;display:flex;gap:8px}
.tgcard .fakebtn{border:1px solid #3c4a40;border-radius:6px;padding:6px 12px;font-size:12px;color:#a9d8a9}
.price{max-width:430px;margin:0 auto;background:var(--panel);border:2px solid var(--green);border-radius:12px;padding:30px;text-align:center}
.price .amount{font-size:42px;font-weight:700;letter-spacing:-.03em}
.price .per{color:var(--muted);font-size:14px}
.price ul{list-style:none;margin:18px 0 22px;text-align:left}
.price li{padding:7px 0 7px 28px;position:relative;font-size:15px}
.price li:before{content:"✓";position:absolute;left:0;color:var(--green);font-weight:700}
.faq{max-width:760px;margin:0 auto}
.faq details{background:var(--panel);border:1px solid var(--line);border-radius:8px;padding:16px 20px;margin-bottom:10px}
.faq summary{font-weight:600;cursor:pointer;font-size:15.5px}
.faq p{margin-top:10px;color:var(--muted);font-size:14.5px}
footer{border-top:1px solid var(--line);padding:28px 0;text-align:center;color:var(--muted);font-size:13.5px}
</style>
</head>
<body>

<nav class="nav">
  <a class="brand" href="/">first<span>bid</span></a>
  <span class="spacer"></span>
  <a class="btn secondary" href="{{ route('login') }}">Log in</a>
  <a class="btn" href="{{ route('register') }}">Start free trial</a>
</nav>

<div class="hero wrap">
  <div class="eyebrow">Account-safe · No auto-apply · You stay in control</div>
  <h1>Apply to Upwork jobs in <em>2 minutes</em> — without risking your account</h1>
  <p class="sub">FirstBid turns real-time job alerts into ready-to-send cover letters written in <b>your</b> voice, from <b>your</b> real projects — delivered straight to your Telegram. You review, paste, and submit.</p>
  <div class="cta-row">
    <a class="btn big" href="{{ route('register') }}">Start 30-day free trial</a>
  </div>
  <p class="note">No credit card required · Works with your existing job alert service</p>
</div>

<div class="flow">
  <div class="wrap">
    <div class="step"><div class="n">01</div><div class="t">Job posted</div><div class="d">Your feed catches it in seconds</div></div>
    <div class="step"><div class="n">02</div><div class="t">AI scores it</div><div class="d">Bad matches filtered out</div></div>
    <div class="step"><div class="n">03</div><div class="t">Letter written</div><div class="d">Your voice, your projects</div></div>
    <div class="step"><div class="n">04</div><div class="t">Telegram ping</div><div class="d">Score, flags, letter, link</div></div>
    <div class="step"><div class="n">05</div><div class="t">You submit</div><div class="d">Paste, personalize, send</div></div>
  </div>
</div>

<section class="block wrap">
  <h2>Speed wins jobs. Bots lose accounts.</h2>
  <p class="lead">Most jobs get 20–50 proposals within the hour. The freelancers who win are early <b>and</b> specific. FirstBid makes you both — legally.</p>
  <div class="cards">
    <div class="card"><div class="ico">⚡</div><h3>From alert to applied in ~2 minutes</h3><p>The moment your job feed fires, FirstBid analyzes the job and writes the proposal. By the time you open the link, your letter is ready to paste.</p></div>
    <div class="card"><div class="ico">✍️</div><h3>Letters in your voice</h3><p>Built from your real profile — your stack, your past projects, your tone. The first two lines address the client's exact problem, because that's all they see in preview.</p></div>
    <div class="card"><div class="ico">🚩</div><h3>Red flags before you waste Connects</h3><p>Unverified payment, 0-hire clients, low ratings, weak budgets — flagged in the alert so you skip bad clients in one glance.</p></div>
    <div class="card"><div class="ico">🎯</div><h3>Match filter saves your quota</h3><p>Set a minimum match score. Jobs below it never reach you — no noise at 3 AM, no Connects burned on poor fits.</p></div>
    <div class="card"><div class="ico">📝</div><h3>Screening questions answered</h3><p>Required screening questions get honest draft answers from your profile — the slowest part of applying, already done.</p></div>
    <div class="card"><div class="ico">📊</div><h3>Everything in one inbox</h3><p>Every job, score, letter, and status in your dashboard — plus instant Telegram delivery to your phone, wherever you are.</p></div>
  </div>
</section>

<section class="block safe">
  <div class="wrap grid">
    <div>
      <h2>Why your account stays safe</h2>
      <ul>
        <li>FirstBid never logs into Upwork — not once, not ever</li>
        <li>No auto-apply, no auto-refresh, no browser bots</li>
        <li>Every proposal is reviewed and submitted by you, by hand</li>
        <li>AI-drafted proposals are allowed — Upwork ships its own AI writing tools</li>
        <li class="no">What we refuse to build: auto-submission. That's what gets accounts banned.</li>
      </ul>
    </div>
    <div class="tgcard">
      <div class="hd">🟢 Score 9 — WordPress plugin developer</div>
      💰 $600 fixed · Israel · ⭐ 4.8 (7 hires)<br>
      <b>Suggested:</b> Bid $550 on the $600 budget<br><br>
      📋 Cover letter (tap to copy):<br>
      <span style="color:#cdd8cf">Your WooCommerce store needs product customization with RTL support — I shipped exactly this: a WooCommerce invitation-builder plugin with full Hebrew RTL handling...</span>
      <div class="btnrow"><span class="fakebtn">🔗 Open job on Upwork</span><span class="fakebtn">Copy letter</span></div>
    </div>
  </div>
</section>

<section class="block wrap">
  <h2>Simple pricing</h2>
  <p class="lead">Try everything free for 30 days. Paid plans launch soon — early users get founder pricing.</p>
  <div class="price">
    <div class="amount">₹0</div>
    <div class="per">for 30 days · then paid plans (coming soon)</div>
    <ul>
      <li>Up to 100 AI cover letters / month</li>
      <li>Instant Telegram alerts with red flags</li>
      <li>Screening question answers</li>
      <li>Job inbox dashboard</li>
      <li>Personal webhook — works with UpHunt, Vibeworker &amp; more</li>
    </ul>
    <a class="btn big" href="{{ route('register') }}" style="width:100%">Start free trial</a>
  </div>
</section>

<section class="block wrap" style="padding-top:0">
  <h2>Questions</h2>
  <p class="lead"></p>
  <div class="faq">
    <details><summary>Is this allowed by Upwork?</summary><p>Yes. FirstBid never interacts with Upwork's platform — no scraping, no login, no automated submission. It prepares your proposal outside Upwork; you submit it yourself by hand. Using AI to draft proposals is normal and allowed — Upwork offers its own AI writing tools. What Upwork prohibits is automated submission, which FirstBid deliberately does not do.</p></details>
    <details><summary>Where do the job alerts come from?</summary><p>You bring your own feed. FirstBid gives you a personal webhook URL that you paste into a job-monitoring service like UpHunt or Vibeworker (many have free trials). Their alerts flow into FirstBid, and FirstBid does the thinking and writing. You can also stay with slower free sources — the pipeline works the same.</p></details>
    <details><summary>Will the letters sound like AI spam?</summary><p>The letters are generated from your profile — your real projects, stack, and results — and open by addressing the client's specific problem. Banned from every letter: "seamless", "leverage", "I am excited", "Dear client". We still recommend personalizing 1–2 lines before submitting; that habit keeps your reply rate high.</p></details>
    <details><summary>Do you need my Upwork password?</summary><p>Never. FirstBid has no connection to your Upwork account at all. That's the entire point of the design.</p></details>
    <details><summary>What happens after the 30-day trial?</summary><p>Your jobs keep collecting in the dashboard, but new letters pause. Paid plans launch soon at freelancer-friendly prices — trial users get notified first and receive founder pricing.</p></details>
  </div>
</section>

<footer>
  <div class="wrap">
    <a class="brand" href="/">first<span>bid</span></a> — apply fast, stay safe · © {{ date('Y') }} FirstBid
  </div>
</footer>

</body>
</html>
