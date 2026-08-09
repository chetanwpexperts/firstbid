// FirstBid.in Upwork Content Script Injector (Cross-Browser V3)

(function () {
  if (window.__firstbid_injected) return;
  window.__firstbid_injected = true;

  console.log('[FirstBid.in] Injecting assistant widget into Upwork page...');

  // 1. Create Floating Button Widget
  function initWidget() {
    if (document.getElementById('firstbid-widget-root')) return;

    const container = document.createElement('div');
    container.id = 'firstbid-widget-root';
    container.innerHTML = `
      <button type="button" class="fb-float-btn" id="fbOpenBtn">
        <span>⚡ FirstBid.in Proposal AI</span>
        <span class="fb-badge">1-Click</span>
      </button>

      <div class="fb-panel-overlay" id="fbOverlay"></div>
      <div class="fb-panel-modal" id="fbModal" style="display: none;">
        <div class="fb-panel-header">
          <div class="fb-panel-title">
            <span style="color: #14a800;">⚡ FirstBid.in</span> AI Proposal Workspace
          </div>
          <button type="button" class="fb-panel-close" id="fbCloseBtn">✕</button>
        </div>

        <div class="fb-panel-body" id="fbPanelBody">
          <div class="fb-loading" id="fbLoadingState" style="display: none;">
            <div class="fb-spinner"></div>
            <div>Analyzing Upwork job description & generating AI proposal...</div>
          </div>

          <div id="fbResultState" style="display: none;">
            <div class="fb-tabs">
              <button type="button" class="fb-tab-btn active" data-tab="fb-tab-hooks">🎯 Opener Hooks</button>
              <button type="button" class="fb-tab-btn" data-tab="fb-tab-letter">✍️ Cover Letter</button>
              <button type="button" class="fb-tab-btn" data-tab="fb-tab-scope">📊 Scope & Milestones</button>
              <button type="button" class="fb-tab-btn" data-tab="fb-tab-qa" id="fbQaTabBtn" style="display: none;">📝 Q&A</button>
            </div>

            <!-- TAB 1: HOOKS -->
            <div class="fb-tab-content active" id="fb-tab-hooks">
              <div id="fbHooksContainer"></div>
            </div>

            <!-- TAB 2: COVER LETTER -->
            <div class="fb-tab-content" id="fb-tab-letter">
              <div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: 700; font-size: 13px;">Generated Proposal Text:</span>
                <button type="button" class="fb-btn-primary" id="fbFillLetterBtn">🚀 Auto-Fill Cover Letter Into Upwork</button>
              </div>
              <div class="fb-card" style="white-space: pre-wrap; font-size: 13px;" id="fbLetterText"></div>
            </div>

            <!-- TAB 3: SCOPE -->
            <div class="fb-tab-content" id="fb-tab-scope">
              <div id="fbScopeContainer"></div>
            </div>

            <!-- TAB 4: Q&A -->
            <div class="fb-tab-content" id="fb-tab-qa">
              <div style="margin-bottom: 12px; font-weight: 700; font-size: 13px;">Screening Questions & AI Answers:</div>
              <div id="fbQaContainer"></div>
            </div>
          </div>

          <div id="fbErrorState" style="display: none; text-align: center; color: #dc2626; padding: 20px;">
            <div id="fbErrorText"></div>
          </div>
        </div>
      </div>
    `;

    document.body.appendChild(container);

    // Event Listeners
    const openBtn = document.getElementById('fbOpenBtn');
    const closeBtn = document.getElementById('fbCloseBtn');
    const overlay = document.getElementById('fbOverlay');
    const modal = document.getElementById('fbModal');

    openBtn.addEventListener('click', () => {
      overlay.style.display = 'block';
      modal.style.display = 'flex';
      handleGenerate();
    });

    const closeModal = () => {
      overlay.style.display = 'none';
      modal.style.display = 'none';
    };

    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);

    // Tab Switching
    container.querySelectorAll('.fb-tab-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        container.querySelectorAll('.fb-tab-btn').forEach(b => b.classList.remove('active'));
        container.querySelectorAll('.fb-tab-content').forEach(c => c.classList.remove('active'));

        btn.classList.add('active');
        const target = document.getElementById(btn.getAttribute('data-tab'));
        if (target) target.classList.add('active');
      });
    });

    // Auto-Fill Cover Letter Button
    document.getElementById('fbFillLetterBtn').addEventListener('click', () => {
      const text = document.getElementById('fbLetterText').innerText;
      if (text) {
        fillUpworkCoverLetter(text);
      }
    });
  }

  // 2. Scrape Job Details from Upwork DOM
  function scrapeUpworkJob() {
    const titleEl = document.querySelector('h1, h2.job-title, .job-title, [data-test="job-title"]');
    const title = titleEl ? titleEl.innerText.trim() : document.title.replace('- Upwork', '').trim();

    const descEl = document.querySelector('.job-description, [data-test="job-description"], .description, section.job-description-section');
    const description = descEl ? descEl.innerText.trim() : (document.body.innerText.slice(0, 1500));

    let budget = 'See job post';
    const budgetEl = document.querySelector('[data-test="budget"], .budget, [data-test="hourly-rate"]');
    if (budgetEl) budget = budgetEl.innerText.trim();

    // Extract screening questions if present on application page
    const screeningQuestions = [];
    document.querySelectorAll('[data-test="question"], .question-item, label[for*="question"]').forEach((qEl, idx) => {
      const qText = qEl.innerText.trim();
      if (qText && qText.length > 8) {
        screeningQuestions.push({ position: idx + 1, question: qText });
      }
    });

    return {
      title,
      description,
      jobUrl: window.location.href,
      budget,
      screeningQuestions
    };
  }

  // 3. Auto-Fill Text Into Upwork React Textarea
  function fillUpworkCoverLetter(text) {
    // Upwork textarea selectors
    const selectors = [
      'textarea[name="coverLetter"]',
      'textarea.up-textarea',
      'textarea[aria-labelledby*="cover-letter"]',
      'textarea[data-test="cover-letter"]',
      'textarea'
    ];

    let textarea = null;
    for (const sel of selectors) {
      textarea = document.querySelector(sel);
      if (textarea && textarea.offsetParent !== null) break;
    }

    if (!textarea) {
      alert('Could not find Upwork cover letter textarea. Please make sure you are on the Upwork Proposal Submit page!');
      return;
    }

    // React native value setter trick
    const nativeSetter = Object.getOwnPropertyDescriptor(window.HTMLTextAreaElement.prototype, 'value').set;
    nativeSetter.call(textarea, text);

    textarea.dispatchEvent(new Event('input', { bubbles: true }));
    textarea.dispatchEvent(new Event('change', { bubbles: true }));
    textarea.focus();

    alert('✅ FirstBid.in proposal auto-filled into Upwork cover letter field!');

    // Notify background script to mark as applied
    chrome.runtime.sendMessage({
      type: 'SYNC_APPLIED',
      jobUrl: window.location.href
    });
  }

  // 4. Trigger Proposal Generation via Service Worker
  function handleGenerate() {
    const loadingState = document.getElementById('fbLoadingState');
    const resultState = document.getElementById('fbResultState');
    const errorState = document.getElementById('fbErrorState');

    loadingState.style.display = 'block';
    resultState.style.display = 'none';
    errorState.style.display = 'none';

    const jobData = scrapeUpworkJob();

    chrome.runtime.sendMessage({ type: 'GENERATE_PROPOSAL', jobData }, (response) => {
      loadingState.style.display = 'none';

      if (!response || !response.success) {
        errorState.style.display = 'block';
        document.getElementById('fbErrorText').innerText = response ? response.error : 'Failed to connect to FirstBid.in extension API.';
        return;
      }

      const p = response.proposal;
      resultState.style.display = 'block';

      // Render Hooks
      const hooksContainer = document.getElementById('fbHooksContainer');
      hooksContainer.innerHTML = '';
      if (p.opener_hooks) {
        const hooks = [
          { name: '🛠️ Problem-Direct Opener', text: p.opener_hooks.problem_direct },
          { name: '📈 Results & Metrics Opener', text: p.opener_hooks.results_first },
          { name: '⚡ Fast Execution Opener', text: p.opener_hooks.fast_delivery }
        ];

        hooks.forEach(h => {
          if (!h.text) return;
          const card = document.createElement('div');
          card.className = 'fb-card';
          card.innerHTML = `
            <div class="fb-card-header">
              <span class="fb-card-title">${h.name}</span>
              <button type="button" class="fb-btn-sm fb-copy-btn">Copy</button>
            </div>
            <div class="fb-card-text">${h.text}</div>
          `;
          card.querySelector('.fb-copy-btn').addEventListener('click', () => {
            navigator.clipboard.writeText(h.text);
            alert('Copied hook to clipboard!');
          });
          hooksContainer.appendChild(card);
        });
      }

      // Render Cover Letter
      document.getElementById('fbLetterText').innerText = p.cover_letter || '';

      // Render Scope & Milestones
      const scopeContainer = document.getElementById('fbScopeContainer');
      scopeContainer.innerHTML = `
        <div class="fb-card">
          <div style="font-weight: 700; color: #14a800; margin-bottom: 6px;">💰 Recommended Bid: ${p.estimated_budget || 'See scope'}</div>
          <div style="font-size: 13px; color: #64748b;">⏱️ Estimated Duration: ${p.estimated_duration || '1-2 weeks'}</div>
        </div>
      `;

      if (p.task_breakdown && p.task_breakdown.length) {
        let taskHtml = '<div style="font-weight: 700; font-size: 12.5px; margin: 10px 0 6px;">📋 Task Breakdown:</div>';
        p.task_breakdown.forEach(t => {
          taskHtml += `<div class="fb-card" style="display: flex; justify-content: space-between; padding: 8px 12px;"><span>${t.task}</span><strong>~${t.hours}h</strong></div>`;
        });
        scopeContainer.innerHTML += taskHtml;
      }

      // Render Q&A if present
      const qaBtn = document.getElementById('fbQaTabBtn');
      const qaContainer = document.getElementById('fbQaContainer');
      if (p.question_answers && p.question_answers.length) {
        qaBtn.style.display = 'block';
        qaContainer.innerHTML = '';
        p.question_answers.forEach((qa, idx) => {
          const div = document.createElement('div');
          div.className = 'fb-card';
          div.innerHTML = `
            <div class="fb-card-header">
              <span class="fb-card-title">Question ${idx + 1}</span>
              <button type="button" class="fb-btn-sm fb-qa-copy">Copy Answer</button>
            </div>
            <div class="fb-card-text">${qa.answer}</div>
          `;
          div.querySelector('.fb-qa-copy').addEventListener('click', () => {
            navigator.clipboard.writeText(qa.answer);
            alert('Answer copied!');
          });
          qaContainer.appendChild(div);
        });
      } else {
        qaBtn.style.display = 'none';
      }
    });
  }

  // Initialize widget when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initWidget);
  } else {
    initWidget();
  }
})();
