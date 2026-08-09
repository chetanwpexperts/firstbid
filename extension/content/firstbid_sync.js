// FirstBid.in Auto-Sync Content Script
(function () {
  const meta = document.querySelector('meta[name="firstbid-token"]');
  if (meta && meta.content) {
    const token = meta.content.trim();
    if (token) {
      chrome.runtime.sendMessage({
        type: 'SAVE_CONFIG',
        webhookToken: token,
        apiBaseUrl: window.location.origin
      }, (res) => {
        if (res && res.success) {
          console.log('[FirstBid.in Extension] Auto-synced user account token successfully!');
        }
      });
    }
  }
})();
