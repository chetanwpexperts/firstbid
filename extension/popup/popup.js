document.addEventListener('DOMContentLoaded', async () => {
  const tokenInput = document.getElementById('webhookToken');
  const urlInput = document.getElementById('apiBaseUrl');
  const form = document.getElementById('configForm');
  const toast = document.getElementById('toast');
  const statusText = document.getElementById('statusText');
  const openSettingsLink = document.getElementById('openSettings');

  // Load configuration from background/storage
  chrome.runtime.sendMessage({ type: 'GET_CONFIG' }, (response) => {
    if (response) {
      tokenInput.value = response.webhookToken || '';
      urlInput.value = response.apiBaseUrl || 'https://firstbidin.com';

      if (response.webhookToken) {
        statusText.innerText = 'Connected & Active';
      } else {
        statusText.innerText = 'Token Required';
      }
    }
  });

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
        statusText.innerText = webhookToken ? 'Connected & Active' : 'Token Required';
        setTimeout(() => { toast.style.display = 'none'; }, 2000);
      }
    });
  });
});
