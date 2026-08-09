document.addEventListener('DOMContentLoaded', async () => {
  const tokenInput = document.getElementById('webhookToken');
  const urlInput = document.getElementById('apiBaseUrl');
  const form = document.getElementById('configForm');
  const toast = document.getElementById('toast');
  const statusText = document.getElementById('statusText');
  const statusDot = document.querySelector('.status-dot');
  const openSettingsLink = document.getElementById('openSettings');

  // Load configuration from background/storage
  chrome.runtime.sendMessage({ type: 'GET_CONFIG' }, async (response) => {
    if (response) {
      tokenInput.value = response.webhookToken || '';
      urlInput.value = response.apiBaseUrl || 'https://firstbidin.com';

      if (response.webhookToken) {
        checkAccountStatus(response.apiBaseUrl, response.webhookToken);
      } else {
        statusText.innerText = 'Token Required';
        if (statusDot) statusDot.className = 'status-dot';
      }
    }
  });

  async function checkAccountStatus(baseUrl, token) {
    try {
      const res = await fetch(`${baseUrl.replace(/\/$/, '')}/api/extension/status`, {
        headers: { 'X-Webhook-Token': token, 'Accept': 'application/json' }
      });
      const data = await res.json();
      if (res.ok && data.connected) {
        if (!data.can_generate) {
          statusText.innerText = 'Trial Ended / Upgrade Required ⛔';
          if (statusDot) statusDot.className = 'status-dot red';
        } else {
          statusText.innerText = `Active (${data.quota_remaining} proposals left)`;
          if (statusDot) statusDot.className = 'status-dot green';
        }
      } else {
        statusText.innerText = 'Invalid Token / Disconnected';
        if (statusDot) statusDot.className = 'status-dot red';
      }
    } catch (e) {
      statusText.innerText = 'Connected to FirstBid.in';
      if (statusDot) statusDot.className = 'status-dot green';
    }
  }

  openSettingsLink.addEventListener('click', (e) => {
    e.preventDefault();
    const baseUrl = urlInput.value || 'https://firstbidin.com';
    chrome.tabs.create({ url: `${baseUrl.replace(/\/$/, '')}/settings` });
  });

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const webhookToken = tokenInput.value.trim();
    const apiBaseUrl = urlInput.value.trim().replace(/\/$/, '');

    chrome.runtime.sendMessage({
      type: 'SAVE_CONFIG',
      webhookToken,
      apiBaseUrl
    }, (res) => {
      if (res && res.success) {
        toast.style.display = 'block';
        if (webhookToken) {
          checkAccountStatus(apiBaseUrl, webhookToken);
        } else {
          statusText.innerText = 'Token Required';
        }
        setTimeout(() => { toast.style.display = 'none'; }, 2000);
      }
    });
  });
});
