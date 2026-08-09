@extends('layout')

@section('title', 'Privacy Policy — FirstBid.in & Chrome Extension')

@section('content')
<div class="glass-panel" style="max-width: 860px; margin: 0 auto 50px; padding: 40px; background: #ffffff;">
  <div style="margin-bottom: 24px; border-bottom: 1px solid var(--border); padding-bottom: 20px;">
    <div style="font-size: 12px; font-family: var(--font-mono); color: var(--upwork-green); font-weight: 800; text-transform: uppercase; margin-bottom: 6px;">
      LEGAL & COMPLIANCE
    </div>
    <h1 style="font-size: 32px; font-weight: 800; color: var(--text-dark); margin: 0;">
      Privacy Policy & Data Security
    </h1>
    <div style="font-size: 13.5px; color: var(--text-muted); margin-top: 6px;">
      Last Updated: {{ date('F j, Y') }} · Applies to FirstBid.in Web Application & Browser Extensions
    </div>
  </div>

  <div style="font-size: 14.5px; color: var(--text-main); line-height: 1.7; display: flex; flex-direction: column; gap: 24px;">
    <section>
      <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 8px;">1. Introduction & Overview</h2>
      <p style="margin: 0;">
        At <strong>FirstBid.in</strong> ("FirstBid", "we", "us", or "our"), we respect your privacy and are committed to protecting the personal data of freelancers, agency owners, and professionals using our web platform and browser extensions. This Privacy Policy details how we collect, use, and protect your information when you access FirstBid.in or install our browser extension ("FirstBid.in — Upwork AI Proposal Assistant").
      </p>
    </section>

    <section>
      <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 8px;">2. Information We Collect</h2>
      <p style="margin-bottom: 10px;">We collect only the minimum necessary data required to deliver our AI proposal generation and job tracking services:</p>
      <ul style="margin-left: 20px; display: flex; flex-direction: column; gap: 6px;">
        <li><strong>Account Data:</strong> Name, email address, and encrypted authentication credentials when you register for a FirstBid.in account.</li>
        <li><strong>Freelancer Profile Data:</strong> Saved portfolio projects, niche skills, and custom proposal templates stored in your account settings.</li>
        <li><strong>Browser Extension Trigger Data:</strong> When you actively click the "Generate Proposal" button on an Upwork job page, the extension reads the public job title, job description, budget, and screening questions from the active tab.</li>
      </ul>
    </section>

    <section>
      <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 8px;">3. How We Use Your Information</h2>
      <p style="margin-bottom: 10px;">Your data is used strictly for the following purposes:</p>
      <ul style="margin-left: 20px; display: flex; flex-direction: column; gap: 6px;">
        <li>To generate tailored AI proposal drafts, opener hooks, subtask effort estimates, and screening question answers.</li>
        <li>To authenticate your session and manage subscription plan limits and proposal quotas.</li>
        <li>To deliver real-time job alerts via email or webhooks as requested by you.</li>
      </ul>
    </section>

    <section style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 12px; padding: 20px;">
      <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 8px;">4. Chrome Extension & Data Privacy Declaration</h2>
      <p style="margin-bottom: 10px;">In full compliance with Google Chrome Web Store Developer Policies:</p>
      <ul style="margin-left: 20px; display: flex; flex-direction: column; gap: 6px;">
        <li><strong>No Personal Tracking:</strong> Our extension does NOT track your browsing history, keystrokes, personal passwords, or private communications.</li>
        <li><strong>No Sale of Personal Data:</strong> We NEVER sell, rent, trade, or monetize user data or browsing content to third parties or advertisers.</li>
        <li><strong>Secure HTTPS Transmission:</strong> All data exchanged between the browser extension and FirstBid servers is encrypted using industry-standard SSL/TLS protocols.</li>
        <li><strong>Human-in-the-Loop Safety:</strong> The extension only populates proposal draft text for your manual review. It never automatically submits proposals or bids on your behalf.</li>
      </ul>
    </section>

    <section>
      <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 8px;">5. Data Storage & Security</h2>
      <p style="margin: 0;">
        We implement rigorous technical safeguards including data encryption at rest and in transit, strict access control, and routine security reviews to protect your data against unauthorized access or disclosure.
      </p>
    </section>

    <section>
      <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 8px;">6. Your Rights & Data Deletion</h2>
      <p style="margin: 0;">
        You have the right to inspect, update, or permanently delete your account data at any time. To request account deletion or data export, contact our support team at <a href="mailto:support@firstbidin.com" style="color: var(--upwork-green); font-weight: 700;">support@firstbidin.com</a>.
      </p>
    </section>

    <section>
      <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 8px;">7. Contact Us</h2>
      <p style="margin: 0;">
        If you have any questions or privacy inquiries regarding this policy, please reach out to us at:
        <br>
        <strong>FirstBid.in Privacy Team</strong><br>
        Email: <a href="mailto:support@firstbidin.com" style="color: var(--upwork-green); font-weight: 700;">support@firstbidin.com</a><br>
        Website: <a href="https://firstbidin.com" target="_blank" style="color: var(--upwork-green); font-weight: 700;">https://firstbidin.com</a>
      </p>
    </section>
  </div>
</div>
@endsection
