<!-- FirstBid Interactive Onboarding Tour Modal -->
<div id="tourModal" class="tour-modal-backdrop" style="display: none;">
  <div class="tour-modal-card">

    <!-- Header / Progress Bar -->
    <div class="tour-modal-header">
      <div style="display: flex; align-items: center; gap: 8px;">
        <div class="tour-brand-icon">⚡</div>
        <span style="font-weight: 800; font-size: 15px; color: var(--text-dark);">FirstBid.in Quick Tour</span>
      </div>
      <div style="display: flex; align-items: center; gap: 12px;">
        <span id="tourStepMeta" style="font-size: 12.5px; font-family: var(--font-mono); font-weight: 700; color: var(--upwork-tint-text); background: var(--upwork-tint); padding: 3px 10px; border-radius: 12px;">Step 1 of 4</span>
        <button type="button" class="tour-close-btn" onclick="closeTourModal()">&times;</button>
      </div>
    </div>

    <!-- Step Progress Line -->
    <div class="tour-progress-track">
      <div id="tourProgressFill" class="tour-progress-fill" style="width: 25%;"></div>
    </div>

    <!-- Slides Container -->
    <div class="tour-slides-viewport">

      <!-- Slide 1: 24h Feed -->
      <div class="tour-slide active" data-slide="1">
        <div class="tour-slide-badge">⚡ Real-Time 24h Feed</div>
        <h2 class="tour-slide-title">Verified Upwork Job Stream</h2>
        <p class="tour-slide-desc">
          FirstBid automatically captures verified Upwork jobs from the last 24 hours. Every job displays the client’s score, hire history, and payment verification status.
        </p>

        <div class="tour-illustration-box">
          <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap;">
            <span class="badge" style="background: var(--upwork-tint); color: var(--upwork-tint-text); font-weight: 800; font-family: var(--font-mono); font-size: 12px;">SCORE 9/10</span>
            <span style="font-weight: 700; color: var(--text-dark); font-size: 14px;">Senior Agentic AI & Web Application Developer</span>
            <span style="color: var(--upwork-tint-text); font-weight: 700; font-size: 12px;">✅ Payment Verified</span>
          </div>
        </div>
      </div>

      <!-- Slide 2: AI Scope & Proposal -->
      <div class="tour-slide" data-slide="2">
        <div class="tour-slide-badge">✨ AI Scope & Proposals</div>
        <h2 class="tour-slide-title">Subtask Hours & Tailored Proposals</h2>
        <p class="tour-slide-desc">
          Click <strong>"Generate Proposal"</strong> to get an instant subtask hours breakdown, optimal bid price recommendation, and a custom proposal tailored to your skills.
        </p>

        <div class="tour-illustration-box">
          <div style="font-size: 12px; font-weight: 800; color: var(--upwork-dark); font-family: var(--font-mono); margin-bottom: 6px;">💡 Recommended Bid: $45.00/hr · ~20h Total</div>
          <div style="display: flex; gap: 6px; flex-wrap: wrap;">
            <span style="background: #ffffff; border: 1px solid var(--border); padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">Backend API (~8h)</span>
            <span style="background: #ffffff; border: 1px solid var(--border); padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">UI & Polish (~12h)</span>
          </div>
        </div>
      </div>

      <!-- Slide 3: Telegram Alerts -->
      <div class="tour-slide" data-slide="3">
        <div class="tour-slide-badge">📲 Instant Telegram Alerts</div>
        <h2 class="tour-slide-title">Never Miss High-Scoring Jobs</h2>
        <p class="tour-slide-desc">
          Connect your Telegram account in <strong>Settings</strong> to receive instant alerts with pre-calculated job scores directly on your phone the second a job is posted.
        </p>

        <div class="tour-illustration-box">
          <div style="display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 20px;">🤖</span>
            <div>
              <div style="font-weight: 800; color: var(--upwork-dark); font-size: 13px;">FirstBid Bot Alert</div>
              <div style="font-size: 12px; color: var(--text-muted);">🔥 Score 9/10 job detected! Proposal ready to send.</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Slide 4: Track Applied -->
      <div class="tour-slide" data-slide="4">
        <div class="tour-slide-badge">🚀 Applied Proposals History</div>
        <h2 class="tour-slide-title">Track Submitted Applications</h2>
        <p class="tour-slide-desc">
          Mark any job as <strong>Applied 🚀</strong> to maintain a verified history of submitted proposals under your clean <strong>/applied</strong> dashboard.
        </p>

        <div class="tour-illustration-box">
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <span style="font-weight: 800; color: var(--upwork-dark); font-size: 14px;">Applied Proposals History</span>
            <span class="badge badge-applied" style="font-size: 12px;">APPLIED ✅</span>
          </div>
        </div>
      </div>

    </div>

    <!-- Footer Controls -->
    <div class="tour-modal-footer">
      <!-- Dots Indicator -->
      <div class="tour-dots">
        <span class="tour-dot active" onclick="goToTourSlide(1)"></span>
        <span class="tour-dot" onclick="goToTourSlide(2)"></span>
        <span class="tour-dot" onclick="goToTourSlide(3)"></span>
        <span class="tour-dot" onclick="goToTourSlide(4)"></span>
      </div>

      <div style="display: flex; gap: 10px;">
        <button type="button" id="tourPrevBtn" class="btn btn-ghost btn-sm" onclick="prevTourSlide()" style="display: none;">Back</button>
        <button type="button" id="tourNextBtn" class="btn btn-sm" onclick="nextTourSlide()">Next Step →</button>
        <button type="button" id="tourFinishBtn" class="btn btn-sm" onclick="finishTour()" style="display: none; background: var(--upwork-green); color: #ffffff;">Get Started 🚀</button>
      </div>
    </div>

  </div>
</div>

<style>
.tour-modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.65);
  backdrop-filter: blur(8px);
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  animation: fadeIn 0.25s ease-out;
}

.tour-modal-card {
  background: #ffffff;
  border: 1px solid var(--border);
  border-radius: 20px;
  max-width: 540px;
  width: 100%;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.tour-modal-header {
  padding: 18px 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid var(--border);
}

.tour-brand-icon {
  width: 28px;
  height: 28px;
  background: var(--upwork-tint);
  border: 1px solid var(--upwork-tint-border);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
}

.tour-close-btn {
  background: none;
  border: none;
  font-size: 24px;
  color: var(--text-muted);
  cursor: pointer;
  line-height: 1;
  padding: 0 4px;
}
.tour-close-btn:hover {
  color: var(--text-dark);
}

.tour-progress-track {
  height: 4px;
  background: #f1f5f9;
  width: 100%;
}
.tour-progress-fill {
  height: 100%;
  background: var(--upwork-green);
  transition: width 0.3s ease;
}

.tour-slides-viewport {
  padding: 28px 24px;
  min-height: 220px;
}

.tour-slide {
  display: none;
  animation: slideFadeIn 0.3s ease;
}
.tour-slide.active {
  display: block;
}

.tour-slide-badge {
  font-family: var(--font-mono);
  font-size: 11.5px;
  font-weight: 800;
  text-transform: uppercase;
  color: var(--upwork-tint-text);
  margin-bottom: 8px;
}

.tour-slide-title {
  font-size: 20px;
  font-weight: 800;
  color: var(--text-dark);
  margin-bottom: 10px;
}

.tour-slide-desc {
  font-size: 14px;
  color: var(--text-muted);
  line-height: 1.6;
  margin-bottom: 20px;
}

.tour-illustration-box {
  background: var(--bg-subtle);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 16px;
}

.tour-modal-footer {
  padding: 16px 24px;
  border-top: 1px solid var(--border);
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #fafafa;
}

.tour-dots {
  display: flex;
  gap: 6px;
}
.tour-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #cbd5e1;
  cursor: pointer;
  transition: all 0.2s ease;
}
.tour-dot.active {
  background: var(--upwork-green);
  width: 20px;
  border-radius: 10px;
}

@keyframes slideFadeIn {
  from { opacity: 0; transform: translateY(6px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
let currentTourSlide = 1;
const totalTourSlides = 4;

function openTourModal() {
  const m = document.getElementById('tourModal');
  if (m) m.style.display = 'flex';
  goToTourSlide(1);
}

function closeTourModal() {
  const m = document.getElementById('tourModal');
  if (m) m.style.display = 'none';
}

function goToTourSlide(step) {
  currentTourSlide = step;
  document.querySelectorAll('.tour-slide').forEach(s => s.classList.remove('active'));
  document.querySelectorAll('.tour-dot').forEach(d => d.classList.remove('active'));

  const activeSlide = document.querySelector(`.tour-slide[data-slide="${step}"]`);
  if (activeSlide) activeSlide.classList.add('active');

  const activeDot = document.querySelectorAll('.tour-dot')[step - 1];
  if (activeDot) activeDot.classList.add('active');

  const meta = document.getElementById('tourStepMeta');
  if (meta) meta.innerText = `Step ${step} of ${totalTourSlides}`;

  const fill = document.getElementById('tourProgressFill');
  if (fill) fill.style.width = `${(step / totalTourSlides) * 100}%`;

  const prevBtn = document.getElementById('tourPrevBtn');
  const nextBtn = document.getElementById('tourNextBtn');
  const finishBtn = document.getElementById('tourFinishBtn');

  if (prevBtn) prevBtn.style.display = step > 1 ? 'inline-block' : 'none';
  if (nextBtn) nextBtn.style.display = step < totalTourSlides ? 'inline-block' : 'none';
  if (finishBtn) finishBtn.style.display = step === totalTourSlides ? 'inline-block' : 'none';
}

function nextTourSlide() {
  if (currentTourSlide < totalTourSlides) {
    goToTourSlide(currentTourSlide + 1);
  }
}

function prevTourSlide() {
  if (currentTourSlide > 1) {
    goToTourSlide(currentTourSlide - 1);
  }
}

function finishTour() {
  closeTourModal();
  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  fetch('{{ route("tour.complete") }}', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': token,
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    }
  }).catch(() => {});
}

document.addEventListener('DOMContentLoaded', function() {
  @auth
    @if(auth()->user()->tour_seen_at === null)
      openTourModal();
    @endif
  @endauth
});
</script>
