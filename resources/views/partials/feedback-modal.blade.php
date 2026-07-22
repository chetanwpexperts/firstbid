<div id="feedbackModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
  <div class="glass-panel" style="max-width: 480px; width: 100%; padding: 28px; position: relative; background: #ffffff; border-color: var(--upwork-tint-border); box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
    <button type="button" onclick="closeFeedbackModal()" style="position: absolute; right: 18px; top: 18px; background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-muted);">✕</button>

    <h2 style="font-size: 20px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">Submit Feedback & Ideas</h2>
    <p style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 20px;">Help us improve FirstBid. Share feature requests, bug reports, or general reviews.</p>

    <form method="POST" action="{{ route('feedback.store') }}">
      @csrf
      <div class="form-group">
        <label class="form-label">Rating</label>
        <select name="rating" required style="font-size: 14px;">
          <option value="5">⭐⭐⭐⭐⭐ Excellent (5/5)</option>
          <option value="4">⭐⭐⭐⭐ Great (4/5)</option>
          <option value="3">⭐⭐⭐ Average (3/5)</option>
          <option value="2">⭐⭐ Needs Work (2/5)</option>
          <option value="1">⭐ Poor (1/5)</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Category</label>
        <select name="category" required style="font-size: 14px;">
          <option value="general">💬 General Feedback</option>
          <option value="feature_request">💡 Feature Request</option>
          <option value="bug">🐛 Bug Report</option>
          <option value="competitor_review">📊 Competitor Comparison</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Your Feedback / Review Message</label>
        <textarea name="message" rows="5" required minlength="10" placeholder="Tell us what features you'd like to see, what works well, or any issues you experienced..."></textarea>
      </div>

      <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
        <button type="button" class="btn btn-ghost" onclick="closeFeedbackModal()">Cancel</button>
        <button type="submit" class="btn">Submit Feedback 🚀</button>
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
}
</script>
