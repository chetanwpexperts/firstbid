<!-- FirstBid Interactive Onboarding Tour Modal -->
<div id="tourModal" class="tour-modal-backdrop" style="display: none;">
  <div class="tour-modal-card">

    <!-- Header / Progress Bar -->
    <div class="tour-modal-header">
      <div style="display: flex; align-items: center; gap: 8px;">
        <div class="tour-brand-icon">⚡</div>
        <div>
          <div style="font-weight: 800; font-size: 15px; color: var(--text-dark); line-height: 1.2;">How FirstBid.in Works</div>
          <div style="font-size: 11px; color: var(--text-muted);">Quick 4-step onboarding guide</div>
        </div>
      </div>
      <div style="display: flex; align-items: center; gap: 12px;">
        <span id="tourStepMeta" style="font-size: 12px; font-family: var(--font-mono); font-weight: 700; color: var(--upwork-tint-text); background: var(--upwork-tint); padding: 3px 10px; border-radius: 12px;">Step 1 of 4</span>
        <button type="button" class="tour-close-btn" onclick="dismissTourModal()">&times;</button>
      </div>
    </div>

    <!-- Step Progress Line -->
    <div class="tour-progress-track">
      <div id="tourProgressFill" class="tour-progress-fill" style="width: 25%;"></div>
    </div>

    <!-- Slides Container -->
    <div class="tour-slides-viewport">

      <!-- Slide 1: Setup Profile -->
      <div class="tour-slide active" data-slide="1">
        <div class="tour-slide-badge">STEP 1 · PROFILE SETUP</div>
        <h2 class="tour-slide-title">1. Add Your Skills & Proposal Profile</h2>
        <p class="tour-slide-desc">
          FirstBid AI generates tailored proposals based on your experience. Visit <strong>Settings</strong> to add your skills, past portfolio projects, and preferred proposal tone.
        </p>

        <div class="tour-illustration-box">
          <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px;">
            <div>
              <div style="font-weight: 700; color: var(--text-dark); font-size: 13.5px;">⚙️ Settings → Proposal Profile</div>
              <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">Define your tech stack & portfolio links</div>
            </div>
            @auth
              <a href="{{ route('settings') }}" class="btn btn-ghost btn-sm" onclick="dismissTourModal()" style="font-weight: 700;">Open Settings ⚙️</a>
            @endauth
          </div>
        </div>
      </div>

      <!-- Slide 2: Telegram Alerts -->
      <div class="tour-slide" data-slide="2">
        <div class="tour-slide-badge">STEP 2 · REAL-TIME ALERTS</div>
        <h2 class="tour-slide-title">2. Connect Telegram for Instant Alerts</h2>
        <p class="tour-slide-desc">
          High-ticket Upwork jobs fill up fast. Connect your Telegram Chat ID in <strong>Settings</strong> to receive instant notifications on your phone the second a 9/10 score job arrives.
        </p>

        <div class="tour-illustration-box">
          <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 36px; height: 36px; background: #0088cc; color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">✈️</div>
            <div>
              <div style="font-weight: 800; color: var(--text-dark); font-size: 13.5px;">Instant Telegram Job Pings</div>
              <div style="font-size: 12px; color: var(--text-muted);">Get pre-calculated scores & bid links on your phone</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Slide 3: Generate Proposal & Scope -->
      <div class="tour-slide" data-slide="3">
        <div class="tour-slide-badge">STEP 3 · AI PROPOSALS & SCOPE</div>
        <h2 class="tour-slide-title">3. Generate AI Proposals & Subtask Hours</h2>
        <p class="tour-slide-desc">
          On any job in your <strong>Inbox</strong>, click <strong>"✨ Generate Proposal"</strong>. FirstBid AI calculates recommended bid pricing, subtask hours, and writes a tailored proposal.
        </p>

        <div class="tour-illustration-box">
          <div style="font-size: 12px; font-weight: 800; color: var(--upwork-dark); font-family: var(--font-mono); margin-bottom: 6px;">💰 Bid Recommendation: $45.00/hr · ~20h Scope</div>
          <div style="display: flex; gap: 6px; flex-wrap: wrap;">
            <span style="background: #ffffff; border: 1px solid var(--border); padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">✨ Custom Cover Letter</span>
            <span style="background: #ffffff; border: 1px solid var(--border); padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">📝 Screening Q&A Drafts</span>
          </div>
        </div>
      </div>

      <!-- Slide 4: Submit & Track -->
      <div class="tour-slide" data-slide="4">
        <div class="tour-slide-badge">STEP 4 · APPLICATION HISTORY</div>
        <h2 class="tour-slide-title">4. Apply on Upwork & Track History</h2>
        <p class="tour-slide-desc">
          Copy your cover letter, click <strong>"Open Job on Upwork ↗"</strong>, and click <strong>"Mark as Applied 🚀"</strong> to track your proposal history cleanly under your <strong>/applied</strong> dashboard.
        </p>

        <div class="tour-illustration-box">
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <span style="font-weight: 800; color: var(--upwork-dark); font-size: 13.5px;">Applied Proposals History</span>
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

      <div style="display: flex; gap: 10px; align-items: center;">
        <button type="button" class="btn btn-ghost btn-sm" onclick="dismissTourModal()" style="font-size: 12.5px; color: var(--text-muted);">Skip Tour</button>
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
  width: 32px;
  height: 32px;
  background: var(--upwork-tint);
  border: 1px solid var(--upwork-tint-border);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
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

function dismissTourModal() {
  const m = document.getElementById('tourModal');
  if (m) m.style.display = 'none';
  localStorage.setItem('firstbid_tour_dismissed', '1');
  
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
  dismissTourModal();
}

document.addEventListener('DOMContentLoaded', function() {
  @auth
    const isDismissedLocal = localStorage.getItem('firstbid_tour_dismissed');
    const isDismissedDb = {{ auth()->user()->tour_seen_at ? 'true' : 'false' }};
    if (!isDismissedLocal && !isDismissedDb) {
      openTourModal();
    }
  @endauth
});
</script>
