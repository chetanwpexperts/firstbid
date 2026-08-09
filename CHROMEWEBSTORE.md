# Chrome Web Store Metadata & Publishing Guide — FirstBidIn

This document contains all official store metadata, permissions justifications, and privacy declarations required to publish **FirstBidIn — Upwork AI Proposal Assistant** to the Google Chrome Web Store.

---

## 📋 Store Listing Details

- **Extension Name**: FirstBidIn — Upwork AI Proposal Assistant
- **Short Name**: FirstBidIn
- **Summary / Short Description**:
  Draft account-safe AI proposals, generate 3 opener hooks, and auto-fill cover letters and screening questions directly inside Upwork in 1 click.
- **Detailed Description**:
  Win more Upwork jobs in 2 minutes with FirstBidIn's account-safe AI proposal assistant.

  FirstBidIn injects a smart proposal workspace widget directly inside Upwork job application pages. With 1 click, FirstBidIn analyzes the job requirements and generates:

  🎯 **3 "First 2 Lines" Opener Hooks**: Pick between Problem-Direct, Results & Metrics, or Fast Execution hooks crafted for Upwork's client preview box.

  ✍️ **Tailored Cover Letter**: Generates a clean, persuasive cover letter based on your specific freelance profile and portfolio skills.

  📊 **AI Scope & Deposit Milestones**: Provides mathematical subtask effort hour breakdowns and recommended Upwork deposit milestone phases.

  📝 **Screening Question Helper**: Drafts answers for client screening questions automatically.

  🚀 **1-Click Auto-Fill**: Automatically populates your cover letter and screening answers directly into Upwork's proposal form without leaving your browser tab.

  🛡️ **100% Account-Safe & Human-in-the-Loop**:
  FirstBidIn never automatically submits proposals or bids on your behalf. You maintain full control to review, edit, and click Submit. FirstBidIn never asks for or stores your Upwork passwords.

---

## 🔒 Permissions & Justification (Required for Review Team)

| Permission | Justification for Chrome Web Store Review Team |
|---|---|
| `storage` | Required to store user preference settings, API endpoint configuration, and session tokens locally within the extension. |
| `activeTab` | Required to detect when the user is viewing an Upwork job application page to display the assistant overlay. |
| `https://www.upwork.com/*` | Required to read public job posting descriptions and auto-fill the cover letter field when the user clicks the Auto-Fill button. |

---

## 🛡️ Privacy & Data Use Disclosures

- **Single Purpose**: To assist freelancers in drafting and auto-filling job proposals on Upwork.
- **Data Collection**: No personal browsing history, financial info, or passwords are collected. The extension only reads job text on Upwork when triggered by the user.
- **Data Transmission**: Data is transmitted securely via HTTPS to the user's authenticated FirstBidIn server endpoint.

---

## 🚀 Publishing Steps to Chrome Web Store

1. Go to the [Chrome Developer Dashboard](https://chrome.google.com/webstore/devconsole).
2. Pay the one-time $5 developer registration fee if not already registered.
3. Click **Add new item** and upload the `firstbid-extension.zip` package (downloaded from your FirstBidIn admin page).
4. Copy-paste the **Store Listing Details** and **Permissions Justification** above into the dashboard form.
5. Click **Submit for Review**. Google typically approves new extensions within 24–48 hours!
6. Once published, copy your store URL (e.g., `https://chromewebstore.google.com/detail/your-extension-id`) and set `CHROME_WEBSTORE_URL` in your `.env` file!
