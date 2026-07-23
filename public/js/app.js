// Global App JavaScript Helpers

function startLoader() {
  const l = document.getElementById('pageLoader');
  if (!l) return;
  l.style.opacity = '1';
  l.style.width = '30%';
  setTimeout(() => { if (l) l.style.width = '70%'; }, 200);
  setTimeout(() => { if (l) l.style.width = '90%'; }, 500);
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

function autoMarkApplied(url) {
  if (!url) return;
  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  fetch(url, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': token,
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    }
  }).catch(() => {});
}

function copyVal(id, btn, markUrl) {
  const el = document.getElementById(id);
  if (!el) return;
  navigator.clipboard.writeText(el.value || el.innerText).then(() => {
    const old = btn.innerText;
    btn.innerText = 'Copied ✓';
    setTimeout(() => btn.innerText = old, 1500);
    if (markUrl) {
      autoMarkApplied(markUrl);
    }
  });
}

function copyText(id, btn, markUrl) {
  copyVal(id, btn, markUrl);
}

function switchTab(tabId, btn) {
  // Hide all tab panels
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
  // Deactivate all tab buttons
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));

  // Activate selected panel and button
  const target = document.getElementById(tabId);
  if (target) target.classList.add('active');
  if (btn) btn.classList.add('active');
}

function openFeedbackModal() {
  const m = document.getElementById('feedbackModal');
  if (m) m.style.display = 'flex';
}

function closeFeedbackModal() {
  const m = document.getElementById('feedbackModal');
  if (m) m.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
  finishLoader();

  // Page navigation loader on link clicks
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
