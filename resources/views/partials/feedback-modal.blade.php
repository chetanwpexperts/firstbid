<div id="feedbackModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(8px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
  <div class="glass-panel" style="max-width: 500px; width: 100%; padding: 28px; position: relative; background: #ffffff; border-color: var(--upwork-tint-border); box-shadow: 0 20px 50px rgba(0,0,0,0.2); border-radius: 20px;">
    <button type="button" onclick="closeFeedbackModal()" style="position: absolute; right: 18px; top: 18px; background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text-muted); line-height: 1;">✕</button>

    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
      <div style="width: 34px; height: 34px; background: var(--upwork-tint); border: 1px solid var(--upwork-tint-border); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px;">💬</div>
      <div>
        <h2 style="font-size: 20px; font-weight: 800; color: var(--text-dark); margin: 0; line-height: 1.2;">How is FirstBid.in Working for You?</h2>
        <div style="font-size: 11.5px; font-family: var(--font-mono); font-weight: 700; color: var(--upwork-tint-text); text-transform: uppercase; margin-top: 2px;">Periodic User Review</div>
      </div>
    </div>

    <p style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 20px; line-height: 1.55;">
      Your feedback helps us continuously improve proposal quality, AI scope accuracy, and features. Please take 30 seconds to rate us!
    </p>

    <form method="POST" action="{{ route('feedback.store') }}" onsubmit="localStorage.setItem('firstbid_last_feedback_prompt', Date.now().toString());">
      @csrf
      <div class="form-group" style="margin-bottom: 16px;">
        <label class="form-label" style="font-weight: 700; font-size: 13px; color: var(--text-dark);">Rating</label>
        <select name="rating" required style="font-size: 14px; padding: 10px; border-radius: 8px;">
          <option value="5">⭐⭐⭐⭐⭐ Excellent (5/5) — Winning More Jobs</option>
          <option value="4">⭐⭐⭐⭐ Great (4/5) — Very Helpful</option>
          <option value="3">⭐⭐⭐ Average (3/5) — Working Fine</option>
          <option value="2">⭐⭐ Needs Work (2/5) — Needs Improvements</option>
          <option value="1">⭐ Poor (1/5) — Encountered Issues</option>
        </select>
      </div>

      <div class="form-group" style="margin-bottom: 16px;">
        <label class="form-label" style="font-weight: 700; font-size: 13px; color: var(--text-dark);">Category</label>
        <select name="category" required style="font-size: 14px; padding: 10px; border-radius: 8px;">
          <option value="general">💬 General Review & Experience</option>
          <option value="feature_request">💡 Feature Request / Idea</option>
          <option value="bug">🐛 Bug Report / Issue</option>
          <option value="competitor_review">📊 AI Proposal Quality Feedback</option>
        </select>
      </div>

      <div class="form-group" style="margin-bottom: 20px;">
        <label class="form-label" style="font-weight: 700; font-size: 13px; color: var(--text-dark);">Your Feedback & Suggestions</label>
        <textarea name="message" rows="4" required minlength="10" placeholder="Tell us what features you'd like to see, what works well, or any issues you experienced..." style="font-size: 13.5px; padding: 12px; border-radius: 10px;"></textarea>
      </div>

      <div style="display: flex; gap: 10px; justify-content: flex-end;">
        <button type="button" class="btn btn-ghost" onclick="closeFeedbackModal()">Remind Me Later</button>
        <button type="submit" class="btn" style="background: var(--upwork-green); color: #ffffff; font-weight: 700;">Submit Feedback 🚀</button>
      </div>
    </form>
  </div>
</div>

<script>
function openFeedbackModal() {
  const m = document.getElementById('feedbackModal');
  if (m) m.style.display = 'flex';
}

function closeFeedbackModal() {
  const m = document.getElementById('feedbackModal');
  if (m) m.style.display = 'none';
  localStorage.setItem('firstbid_last_feedback_prompt', Date.now().toString());
}

document.addEventListener('DOMContentLoaded', function() {
  @auth
    // Check if onboarding tour is currently active
    const tourModal = document.getElementById('tourModal');
    const isTourActive = tourModal && tourModal.style.display !== 'none';

    if (!isTourActive) {
      const lastPrompt = localStorage.getItem('firstbid_last_feedback_prompt');
      const threeDaysMs = 3 * 24 * 60 * 60 * 1000; // 72-hour interval
      const now = Date.now();

      if (!lastPrompt || (now - parseInt(lastPrompt, 10)) > threeDaysMs) {
        setTimeout(function() {
          // Re-check tour modal state right before popping up
          if (!tourModal || tourModal.style.display === 'none') {
            openFeedbackModal();
          }
        }, 2000);
      }
    }
  @endauth
});
</script>
