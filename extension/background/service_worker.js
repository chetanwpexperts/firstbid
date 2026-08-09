// FirstBid.in Extension Background Service Worker (Manifest V3)

chrome.runtime.onInstalled.addListener(() => {
  console.log('[FirstBid.in] Extension installed successfully!');
});

// Listener for message passing between content scripts, popup, and background
chrome.runtime.onMessage.addListener((message, sender, sendResponse) => {
  if (message.type === 'GET_CONFIG') {
    chrome.storage.sync.get(['apiBaseUrl', 'webhookToken'], (data) => {
      sendResponse({
        apiBaseUrl: data.apiBaseUrl || 'https://firstbidin.com',
        webhookToken: data.webhookToken || ''
      });
    });
    return true; // Keep message channel open for async response
  }

  if (message.type === 'SAVE_CONFIG') {
    chrome.storage.sync.set({
      apiBaseUrl: message.apiBaseUrl,
      webhookToken: message.webhookToken
    }, () => {
      sendResponse({ success: true });
    });
    return true;
  }

  if (message.type === 'GENERATE_PROPOSAL') {
    (async () => {
      try {
        const { apiBaseUrl, webhookToken } = await chrome.storage.sync.get(['apiBaseUrl', 'webhookToken']);
        const baseUrl = (apiBaseUrl || 'https://firstbidin.com').replace(/\/$/, '');

        if (!webhookToken) {
          sendResponse({ success: false, error: 'Please set your FirstBid.in Webhook Token in the extension settings.' });
          return;
        }

        const endpoint = `${baseUrl}/api/extension/generate`;
        const res = await fetch(endpoint, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Webhook-Token': webhookToken
          },
          body: JSON.stringify(message.jobData)
        });

        const data = await res.json();
        if (!res.ok) {
          sendResponse({ success: false, error: data.error || data.message || 'Failed to generate proposal.' });
          return;
        }

        sendResponse({ success: true, proposal: data });
      } catch (err) {
        console.error('[FirstBid.in] API Error:', err);
        sendResponse({ success: false, error: 'Network error connecting to FirstBid.in API.' });
      }
    })();
    return true;
  }

  if (message.type === 'SYNC_APPLIED') {
    (async () => {
      try {
        const { apiBaseUrl, webhookToken } = await chrome.storage.sync.get(['apiBaseUrl', 'webhookToken']);
        const baseUrl = (apiBaseUrl || 'https://firstbidin.com').replace(/\/$/, '');

        if (!webhookToken) return;

        await fetch(`${baseUrl}/api/jobs/sync-applied`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Webhook-Token': webhookToken
          },
          body: JSON.stringify({
            ciphertext: message.ciphertext,
            job_url: message.jobUrl
          })
        });
        sendResponse({ success: true });
      } catch (e) {
        console.error('[FirstBid.in] Sync applied failed:', e);
        sendResponse({ success: false });
      }
    })();
    return true;
  }
});
