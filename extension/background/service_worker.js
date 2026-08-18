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

        // Already generated previously (cached) — return immediately.
        if (data.status === 'ready') {
          sendResponse({ success: true, proposal: data });
          return;
        }

        // Otherwise the server queued the generation — poll until it's done.
        const jobId = data.job_id;
        const statusUrl = `${baseUrl}/api/extension/generate/${jobId}/status`;
        const pollIntervalMs = 1500;
        const maxAttempts = 40; // ~60s ceiling, matches previous single-request timeout

        for (let attempt = 0; attempt < maxAttempts; attempt++) {
          await new Promise((resolve) => setTimeout(resolve, pollIntervalMs));

          const pollRes = await fetch(statusUrl, {
            headers: { 'X-Webhook-Token': webhookToken, 'Accept': 'application/json' }
          });
          const pollData = await pollRes.json();

          if (pollData.status === 'ready') {
            sendResponse({ success: true, proposal: pollData });
            return;
          }
          if (pollData.status === 'failed') {
            sendResponse({ success: false, error: pollData.error || 'Failed to generate proposal.' });
            return;
          }
          // status === 'processing' — keep polling
        }

        sendResponse({ success: false, error: 'Generation is taking longer than expected. Please try again in a moment.' });
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
